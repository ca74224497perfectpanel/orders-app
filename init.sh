if ! docker &>/dev/null; then
  echo 'Докер не запущен!'
  exit
fi

if docker ps -a | grep -iq 'ca74224497/orders-app:v10'; then
  echo 'Образ уже развернут.'
else
  echo 'Запуск контейнера...'
  docker run -dit \
             --name application \
             -p 8080:80 \
             -v=/Users/mihail/web/test/www:/var/www/application ca74224497/orders-app:v10
fi

echo 'Запуск критических служб приложения...'
docker exec -it application bash