## Prerequisites

- PHP ^7.3
- [Composer ^2.0](https://getcomposer.org/)
- [Docker](https://docs.docker.com/get-docker/)
- [Node v11.15](https://nodejs.org/en/download)

## To run locally

1. Comment out the PDO line in `mat.settings.php`.
2. Copy `.env.example` to `.env` and provide some values for the MySQL variables. Doesn't really matter
what you use, except that MYSQL_HOSTNAME should match docker-compose service name for MySQL ("database").
3. Start docker-compose `docker-compose up -d --build`.
4. Install drupal `docker-compose exec drupal drush site-install minimal --site-name "Measure Authoring Tool" --existing-config -y`.
5. Clear the cache `docker-compose exec drupal drush cr`.
6. Visit `http://localhost:8080` to view the site. See next section for login info.

## Logging into local site

The drush site installer command generates an admin username and password. You can optionally store this, or just use drush to generate a one-time login link:
- `docker-compose exec drupal drush uli`. That command will generate a URL like `http://default/user/reset/1/1581541762/VGGpYbY3vxoFuzAIuXII7K2csKjpqCN9tU4Ed-fWos8/login`.
- Copy everything after `default` and past that after the site url.

## Local development setup

1. Comment out the PDO line in `mat.settings.php`.
2. Comment out the volumes line in `docker-compose.yml`.
3. Install composer dependencies `composer install`
4. Install theme dependencies `cd html/themes/custom/emat && npm install`
5. Build the theme `npm run-script build`.
6. Copy `.env.example` to `.env` and provide some values for the MySQL variables. Doesn't really matter
what you use, except that MYSQL_HOSTNAME should match docker-compose service name for MySQL ("database").
7. Start docker-compose `docker-compose up -d --build`.
8. Run drupal installer `docker-compose exec drupal drush si`. You'll have to provide some values here for MySQL username and password, etc.
9. Import Drupal config `docker-compose exec drupal drush cim -y`.

## Local theming

The `eMAT` theme uses the atomic design principle, built with [patternlab](https://patternlab.io/). These components are
then consumed by Drupal templates. Unfortunately the base theme project that this was built on has been deprecated, so
updates to the package dependencies here is tricky. Development of theme components is still possible, but
it may be worth it to put in some time to update the underlying design system in the future.

0. You'll need node v11, as specified in the prerequisites section at the top of the README.
1. Navigate to the theme directory `cd html/themes/custom/emat`.
2. Install package dependencies `npm install`
3. Start pattern lab `npm start`.
4. Once ready, you should get a URL like `http://localhost:3000/pattern-lab/public`.

For modifying existing components, you should be able to work exclusively within patterlab, but when adding new
components, you'll need to first theme the element in patternlab, and then create the corresponding Drupal template
to consume that themed component.

## Usage

With `composer require ...` you can download new dependencies to your
installation.

```
composer require drupal/devel:~1.0
```

## Updating Drupal Core

Follow the steps below to update your core files.

1. Run `composer update drupal/core drupal/core-dev --with-dependencies` to update Drupal Core and its dependencies.
2. Run `git diff` to determine if any of the scaffolding files have changed.
   Review the files for any changes and restore any customizations to
  `.htaccess` or `robots.txt`.
3. Commit everything all together in a single commit, so `html` will remain in
   sync with the `core` when checking out branches or running `git bisect`.
4. In the event that there are non-trivial conflicts in step 2, you may wish
   to perform these steps on a branch, and use `git merge` to combine the
   updated core files with your customized files. This facilitates the use
   of a [three-way merge tool such as kdiff3](http://www.gitshah.com/2010/12/how-to-setup-kdiff-as-diff-tool-for-git.html). This setup is not necessary if your changes are simple;
   keeping all of your modifications at the beginning or end of the file is a
   good strategy to keep merges easy.

## FAQ

### Should I commit the contrib modules I download?

Composer recommends **no**. They provide [argumentation against but also
workrounds if a project decides to do it anyway](https://getcomposer.org/doc/faqs/should-i-commit-the-dependencies-in-my-vendor-directory.md).

### Should I commit the scaffolding files?

The [Drupal Composer Scaffold](https://github.com/drupal/core-composer-scaffold) plugin can download the scaffold files (like
index.php, update.php, …) to the web/ directory of your project. If you have not customized those files you could choose
to not check them into your version control system (e.g. git). If that is the case for your project it might be
convenient to automatically run the drupal-scaffold plugin after every install or update of your project. You can
achieve that by registering `@composer drupal:scaffold` as post-install and post-update command in your composer.json:

```json
"scripts": {
    "post-install-cmd": [
        "@composer drupal:scaffold",
        "..."
    ],
    "post-update-cmd": [
        "@composer drupal:scaffold",
        "..."
    ]
},
```
### How can I apply patches to downloaded modules?

If you need to apply patches (depending on the project being modified, a pull
request is often a better solution), you can do so with the
[composer-patches](https://github.com/cweagans/composer-patches) plugin.

To add a patch to drupal module foobar insert the patches section in the extra
section of composer.json:
```json
"extra": {
    "patches": {
        "drupal/foobar": {
            "Patch description": "URL or local path to patch"
        }
    }
}
```
