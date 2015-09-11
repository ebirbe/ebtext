PWD=$(dirname $0);
DESTDIR="/etc/udev/rules.d/"


cp $PWD/ttyusb-map.sh $DESTDIR
cp $PWD/80-ttyusb-map.rules $DESTDIR

chmod 755 $DESTDIR/ttyusb-map.sh

