#!/bin/bash

# Параметры по умолчанию
name=application
port=8080
version=11 # Список доступных версий по ссылке: https://hub.docker.com/repository/docker/ca74224497/orders-app

# Регулярное выражение для проверки числа
numeric='^[0-9]+$'

for param in "$@"; do
  IFS='=' read -ra split <<< "$param"

  if [[ $param == 'help' ]]; then
    echo ''
    echo '**************************************************{ СПРАВКА ПО ИСПОЛЬЗОВАНИЮ КОМАНДЫ }**************************************************'
    echo ''
    echo 'Пример использования:'
    echo "  $0 -name=application -port=8080 -version=10"
    echo ''
    echo 'Ключи'
    echo '  -name: Имя контейнера (его можно использовать как псевдоним в командах Docker, например: "docker stop %name%")'
    echo '  -port: Номер порта через который приложение будет доступно на хосте-хозяине (система, в которой запускается контейнер)'
    echo '  -version: Версия контейнера приложения'
    echo ''
    echo '****************************************************************************************************************************************'
    exit
  elif [[ $param == "name="* ]]; then
    IFS='=' read -ra split <<< "$param"

    if [[ ${#split[1]} == 0 ]]; then
      echo 'Недопустимое имя приложения!'
      exit
    fi

    # Устанавливаем имя для контейнера
    name=${split[1]}
  elif [[ $param == "port="* ]]; then
    if ! [[ ${split[1]} =~ $numeric ]]; then
      echo 'Порт должен быть положительным числом!'
      exit
    fi

    if ((split[1] < 0 || split[1] > 65536)); then
        echo 'Порт должен быть в диапазоне от 0 до 65536 (не забывайте, что некоторые порты могут быть заняты другими службами)'
    fi

    # Устанавливаем номер порта
    port=${split[1]}
  elif [[ $param == "version="* ]]; then
    if ! [[ ${split[1]} =~ $numeric ]]; then
      echo 'Версия контейнера должна быть числом!'
      exit
    fi

    # Устанавливаем версию контейнера приложения
    version=${split[1]}
    echo $version
  else
    echo "Неверный входной параметр '$param', для получения справки используйте команду: '$0 help'"
    exit
  fi
done

if ! docker &>/dev/null; then
  echo 'Докер не запущен!'
  exit
fi

if docker ps -a | grep -iq "ca74224497/orders-app:v${version}"; then
  echo 'Контейнер уже развернут, поэтому требуется перезапуск.'
  echo 'Останавливаю контейнер...'
  docker stop $name
  echo 'Удаляю контейнер...'
  docker rm $name
fi

echo 'Запуск контейнера...'
docker run -dit \
           --name ${name} \
           -p ${port}:80 \
           -v=/Users/mihail/web/test/www:/var/www/application \
           ca74224497/orders-app:v${version}

echo "Контейнер был запущен со следующими параметрами: name=$name, port=$port, version=$version"
echo "Приложение доступно по адресу: http://localhost:$port"

echo 'Запуск критически важных служб приложения...'
docker exec -it application bash