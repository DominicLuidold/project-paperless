#!/bin/bash -e

source .env

if [ -e .env.local ]; then
    source .env.local
fi

docker build \
    -t ${PROJECT_NAME}-node-local \
    --build-arg NODE_VERSION=${NODE_VERSION} \
    ./docker/frontend/.

docker run --rm -it \
    --publish 4200:4200 \
    --volume ${PWD}/frontend:/usr/src/app \
    --volume /dev/shm:/dev/shm \
    --workdir /usr/src/app \
    --network "${PROJECT_NAME}_default" \
    --privileged \
    --name "${PROJECT_NAME}-frontend" \
    ${PROJECT_NAME}-node-local /bin/bash
