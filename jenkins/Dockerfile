FROM jenkins/jenkins
ENV JAVA_OPTS -Djenkins.install.runSetupWizard=true
COPY plugins.txt /usr/share/jenkins/ref/plugins.txt
RUN jenkins-plugin-cli -f /usr/share/jenkins/ref/plugins.txt