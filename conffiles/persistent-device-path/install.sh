PWD=$(dirname $0);
DESTDIR="/etc/udev/rules.d/"
SCRIPT_FILE="ttyusb-map.pm"
RULES_FILE="99-ttyusb-map.rules"

cp $PWD/$SCRIPT_FILE $DESTDIR
cp $PWD/$RULES_FILE $DESTDIR

chmod 755 $DESTDIR/$SCRIPT_FILE

