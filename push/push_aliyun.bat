echo "up code to test website ......6666666666666666666666666666666666666666666666666888888888888888888888888888888888888888888888" &
rsync -azuv --progress --no-super <./pass.pwd ../* rsync://tom@121.40.251.65:8000/data/log-admin --exclude-from=skip.txt