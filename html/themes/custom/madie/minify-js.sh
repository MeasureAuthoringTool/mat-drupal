#!/bin/bash

# Source and destination directories
SRC_DIR="components/_patterns"
DEST_DIR="dist"

# Find all .js files in the source directory
find $SRC_DIR -name '*.js' | while read jsfile; do
    # Create the same folder structure in the destination directory
    destfile="$DEST_DIR/${jsfile#$SRC_DIR/}"

    # Create the destination directory if it doesn't exist
    mkdir -p "$(dirname "$destfile")"

    # Minify and write to the destination directory
    npx uglifyjs "$jsfile" --compress --mangle --output "${destfile%.js}.js"
done
