#!/bin/bash

SITE_IMAGES="$1"
TMP_FOLDER="$SITE_IMAGES/tmp"
IMAGES_FOLDER="$SITE_IMAGES/f"
THUMB_FOLDER="$SITE_IMAGES/s"

function normalPic {
    FILENAME=$1/$2
    DESTINATION=$3/$2
    echo "Resizing normal $2.."
    convert $FILENAME -resize 640x480 $DESTINATION
    echo 'Done.'
}

function thumbnail {
    FILENAME=$1/$2
    DESTINATION=$3/$2
    echo "Resizing thumb $2.."
    convert $FILENAME -resize 160x120 $DESTINATION
    echo 'Done.'
}

function delete {
    FILENAME=$1/$2
    echo 'Deleting temporary file..'
    rm $FILENAME
    echo "Removed $FILENAME."
}

for pic in $(ls $TMP_FOLDER); do
    normalPic $TMP_FOLDER $pic $IMAGES_FOLDER
    thumbnail $TMP_FOLDER $pic $THUMB_FOLDER
    delete $TMP_FOLDER $pic
done
