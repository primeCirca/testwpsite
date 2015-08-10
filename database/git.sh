#!/bin/bash
# declare variables (FilePath: normally it's run under the wp-admin context)
FilePath=$PWD/
Branch=content-monitor
# cp the new file
echo Copy file from ../wp-content/uploads/recorded-queries-devsite.sql to this current folder ../database/recorded-queries-devsite.sql
cp ../wp-content/uploads/recorded-queries-devsite.sql ../wp-content/recorded-queries-devsite.sql
#implement the github flow simply
echo Switch to branch \'$Branch\'
git checkout $Branch
echo Add the file to index
git add *
echo Commit the changes in this directory \'$FilePath\':
git commit -m "Auto commit happening when the WP content (via admin GUI) is changed"
# cache credential forever
git config credential.helper store
echo Push to the Github repo:
git push origin $Branch
