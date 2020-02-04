pipeline {
  agent any

  stages {
    stage('Push drupal to ECR') {
      steps {
        sh "\$(aws ecr get-login --no-include-email --region us-east-1)"
        sh "docker build -t ${ECR_REPO}drupal-ecr ."
        sh "docker tag ${ECR_REPO}drupal-ecr:latest ${ECR_URL}/${ECR_REPO}drupal-ecr:latest"
        sh "docker push ${ECR_URL}/${ECR_REPO}drupal-ecr:latest"    
      }   
    }
  }
}