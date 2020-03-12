pipeline {
  agent any

  stages {
    stage('Push drupal to ECR') {
      steps {
        sh "aws ecr get-login-password --region us-east-1 | docker login --username AWS --password-stdin ${ECR_URL}/${ECR_REPO}"
        sh "docker build -t ${ECR_REPO} ."
        sh "docker tag ${ECR_REPO}:latest ${ECR_URL}/${ECR_REPO}:latest"
        sh "docker push ${ECR_URL}/${ECR_REPO}:latest"    
      }   
    }
  }
}
