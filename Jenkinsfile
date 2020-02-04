pipeline {
  agent any

  stages {
    stage('Push drupal to ECR') {
      steps {
        sh "sudo \$(aws ecr get-login --no-include-email --region us-east-1)"
        sh "sudo docker build --build-arg JAR_FILE=target/*.jar -t ${ECR_REPO}drupal-ecr ."
        sh "sudo docker tag ${ECR_REPO}drupal-ecr:latest ${ECR_URL}/${ECR_REPO}drupal-ecr:latest"
        sh "sudo docker push ${ECR_URL}/${ECR_REPO}drupal-ecr:latest"    
      }   
    }
  }
}