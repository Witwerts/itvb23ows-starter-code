FROM python:3.9.19

WORKDIR /main

COPY ./ai /main

RUN pip install flask

EXPOSE 5000

CMD ["flask", "--app", "app", "run", "--host=0.0.0.0"]