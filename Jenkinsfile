pipeline {
  agent any
  stages {
    stage('Checkout') {
      steps {
        git(branch: 'main', url: 'https://github.com/TuanDangDuc/Ecom-Project-BE.git')
      }
    }

    stage('Build Docker image') {
      steps {
        script {
          docker.withRegistry('https://index.docker.io/v1/', 'dockerlogin') {

            def commitHash = env.GIT_COMMIT.take(7)

            def dockerImage = docker.build(
              "ductuanbl2000/be-ecom-app:${commitHash}",
              "."
            )

            dockerImage.push()
            dockerImage.push("latest")
            dockerImage.push("dev")
          }
        }

      }
    }
    stage('trigger deploy') {
      steps {
        script {
          build job: 'DeployEcom', wait: false
        }

      }
    }
  }
  post {
    success {
      echo 'Build and push Docker image SUCCESS'
    }

    failure {
      echo 'Build FAILED'
    }

  }
}
