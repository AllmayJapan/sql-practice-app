#!/bin/bash

curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash

\. "$HOME/.nvm/nvm.sh"

nvm install 24

node -v # "v24.12.0"が表示される。

npm -v # "11.6.2"が表示される。

cd src/

npm init -y

npm install monaco-editor@0.55.1

cd ../

docker compose build

docker compose up -d
