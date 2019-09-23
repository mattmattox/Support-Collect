#!/bin/bash

echo "Starting auto build process..."

cd ./server
docker build -t registry.support.tools/supportcollect/server:"$GIT_BRANCH":"$BUILD_NUMBER" .
docker push registry.support.tools/supportcollect/server:"$GIT_BRANCH":"$BUILD_NUMBER"
cd ..
