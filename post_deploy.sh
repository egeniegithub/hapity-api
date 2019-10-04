SCRIPT=$(readlink -f "$0")
# Absolute path this script is in
SCRIPTPATH=$(dirname "$SCRIPT")
find $SCRIPTPATH/ -type d -exec chmod 775 {} \;
find $SCRIPTPATH/ -type f -exec chmod 664 {} \;
chmod +x $SCRIPTPATH/post_deploy.sh
