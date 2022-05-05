@echo off

net use \\172.16.160.10\crf\SOP /persistent:YES

set /P month="Please input month : "
set /P year="Please input year :"

set year=%year:~-2%
set server=\\172.16.160.10\crf\SOP\
set headerDbf=%server%CFH_%year%%month%.DBF
set dedDbf=%server%DED_%year%%month%.DBF
set copyTo=\\172.16.46.135\cwo\files\SOP\SOP

echo "Copying files ..."
copy %headerDbf% %copyTo%
copy %dedDbf% %copyTo%
