#!/bin/sh

git pull
find * -type f | xargs chmod 644
find * -type d | xargs chmod 755
find * -name '*.sh' | xargs chmod 755
