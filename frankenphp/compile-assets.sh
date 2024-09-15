#!/bin/sh
set -e

echo "Installing npm dependencies..."
npm install

echo "Compiling assets..."
npm run build

echo "Assets have been compiled successfully."
