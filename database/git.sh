#!/bin/bash
# declare variables
FilePath=$PWD/
#implement the github flow simply
echo Switch to branch \'content-monitor\'
git checkout content-monitor
echo Add the file to index
git add *
echo Commit the changes in this directory \'$FilePath\':
git commit -m "Auto commit happening when the WP content (via admin GUI) is changed"
# cache credential forever
git config credential.helper store
echo Push to the Github repo:
git push origin content-monitor
