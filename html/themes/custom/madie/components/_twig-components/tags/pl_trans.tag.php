<?php
/**
 * @file
 * Add "Project_trans_Node" class.
 *
 * Code based on `Drupal\Core\Template\TwigNodeTrans` and
 * `Drupal\Core\Template\TwigTransTokenParser` from Drupal 8 core.
 */

use Twig\Compiler;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Node\TextNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;
use Twig\Node\PrintNode;
use Twig\Node\Expression\FilterExpression;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Expression\TempNameExpression;

// These files are loaded three times and we can't re-set a class.
if (!class_exists("Project_pl_trans_Node")) {

  /**
   * Class Project_trans_Node.
   */
  class Project_pl_trans_Node extends Node {

    /**
     * {@inheritdoc}
     */
    public function __construct(Node $body, Node $plural = NULL, AbstractExpression $count = NULL, AbstractExpression $options = NULL, $lineno, $tag = NULL) {
      parent::__construct(array(
        'count' => $count,
        'body' => $body,
        'plural' => $plural,
        'options' => $options,
      ), array(), $lineno, $tag);
    }

    /**
     * {@inheritdoc}
     */
    public function compile(Compiler $compiler) {
      list($singular) = $this->compileString($this->getNode('body'));

      $compiler->addDebugInfo($this);
      $compiler->write('echo ');
      // Write the singular text parameter.
      $compiler->subcompile($singular);
      // End writing.
      $compiler->raw(";\n");
    }

    /**
     * Extracts the text and tokens for the "trans" tag.
     *
     * @param Node $body
     *   The node to compile.
     *
     * @return array
     *   Returns an array containing the two following parameters:
     *   - string $text
     *       The extracted text.
     *   - array $tokens
     *       The extracted tokens as new Twig_Node_Expression_Name instances.
     */
    protected function compileString(Node $body) {
      if ($body instanceof NameExpression || $body instanceof ConstantExpression || $body instanceof TempNameExpression) {
        return array($body, array());
      }

      $tokens = array();
      if (count($body)) {
        $text = '';

        foreach ($body as $node) {
          if (get_class($node) === 'Twig_Node' && $node->getNode(0) instanceof \Twig_Node_SetTemp) {
            $node = $node->getNode(1);
          }

          if ($node instanceof PrintNode) {
            $n = $node->getNode('expr');
            while ($n instanceof FilterExpression) {
              $n = $n->getNode('node');
            }

            $args = $n;

            // Support TwigExtension->renderVar() function in chain.
            if ($args instanceof FunctionExpression) {
              $args = $n->getNode('arguments')->getNode(0);
            }

            // Detect if a token implements one of the filters reserved for
            // modifying the prefix of a token. The default prefix used for
            // translations is "@". This escapes the printed token and makes
            // them safe for templates.
            // @see TwigExtension::getFilters()
            $argPrefix = '@';
            while ($args instanceof FilterExpression) {
              switch ($args->getNode('filter')->getAttribute('value')) {
                case 'placeholder':
                  $argPrefix = '%';
                  break;
              }
              $args = $args->getNode('node');
            }
            if ($args instanceof \Twig_Node_Expression_GetAttr) {
              $argName = array();
              // Reuse the incoming expression.
              $expr = $args;
              // Assemble a valid argument name by walking through the
              // expression.
              $argName[] = $args->getNode('attribute')->getAttribute('value');
              while ($args->hasNode('node')) {
                $args = $args->getNode('node');
                if ($args instanceof NameExpression) {
                  $argName[] = $args->getAttribute('name');
                }
                else {
                  $argName[] = $args->getNode('attribute')->getAttribute('value');
                }
              }
              $argName = array_reverse($argName);
              $argName = implode('.', $argName);
            }
            else {
              $argName = $n->getAttribute('name');
              if (!is_null($args)) {
                $argName = $args->getAttribute('name');
              }
              $expr = new NameExpression($argName, $n->getLine());
            }
            $placeholder = sprintf('%s%s', $argPrefix, $argName);
            $text .= $placeholder;
            $expr->setAttribute('placeholder', $placeholder);
            $tokens[] = $expr;
          }
          else {
            $text .= $node->getAttribute('data');
          }
        }
      }
      else {
        $text = $body->getAttribute('data');
      }

      return array(
        new Node(
          array(new ConstantExpression(trim($text), $body->getTemplateLine()))
        ),
        $tokens,
      );
    }

  }

}

// These files are loaded three times and we can't re-set a class.
if (!class_exists("Project_pl_trans_TokenParser")) {

  /**
   * Class Project_pl_trans_TokenParser.
   */
  class Project_pl_trans_TokenParser extends AbstractTokenParser {

    /**
     * {@inheritdoc}
     */
    public function parse(Token $token) {
      $lineno = $token->getLine();
      $stream = $this->parser->getStream();
      $body = NULL;
      $options = NULL;
      $count = NULL;
      $plural = NULL;

      if (!$stream->test(Token::BLOCK_END_TYPE) && $stream->test(Token::STRING_TYPE)) {
        $body = $this->parser->getExpressionParser()->parseExpression();
      }
      if (!$stream->test(Token::BLOCK_END_TYPE) && $stream->test(Token::NAME_TYPE, 'with')) {
        $stream->next();
        $options = $this->parser->getExpressionParser()->parseExpression();
      }
      if (!$body) {
        $stream->expect(Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideForFork'));
        if ('plural' === $stream->next()->getValue()) {
          $count = $this->parser->getExpressionParser()->parseExpression();
          $stream->expect(Token::BLOCK_END_TYPE);
          $plural = $this->parser->subparse(array($this, 'decideForEnd'), TRUE);
        }
      }

      $stream->expect(Token::BLOCK_END_TYPE);

      $this->checkTransString($body, $lineno);

      $node = new Project_pl_trans_Node($body, $plural, $count, $options, $lineno, $this->getTag());

      return $node;
    }

    /**
     * Detect a 'plural' switch or the end of a 'trans' tag.
     */
    public function decideForFork($token) {
      return $token->test(array('plural', 'endtrans'));
    }

    /**
     * Detect the end of a 'trans' tag.
     */
    public function decideForEnd($token) {
      return $token->test('endtrans');
    }

    /**
     * {@inheritdoc}
     */
    public function getTag() {
      return 'trans';
    }

    /**
     * Ensure that any nodes that are parsed are only of allowed types.
     *
     * @param Node $body
     *   The expression to check.
     * @param int $lineno
     *   The source line.
     *
     * @throws SyntaxError
     *   Twig error.
     */
    protected function checkTransString(Node $body, $lineno) {
      foreach ($body as $node) {
        if (
          $node instanceof TextNode
          ||
          ($node instanceof PrintNode && $node->getNode('expr') instanceof NameExpression)
          ||
          ($node instanceof PrintNode && $node->getNode('expr') instanceof \Twig_Node_Expression_GetAttr)
          ||
          ($node instanceof PrintNode && $node->getNode('expr') instanceof \Twig_Node_Expression_Filter)
        ) {
          continue;
        }

        throw new SyntaxError(sprintf('The text to be translated with "trans" can only contain references to simple variables'), $lineno);
      }
    }

  }

}
