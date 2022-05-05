@echo off

net use \\172.16.160.10\crf  /user:public/public /persistent:YES

set headerDbf=CFH_2010.DBF
set dedDbf=DED_2010.DBF
set dbfPath=\\172.16.160.10\crf\SOP\
set copyTo=\\172.16.46.135\cwo\files\SOP\SOP

REM echo %headerDbf%
REM echo %dedDbf%
REM echo %dbfPath%
REM echo %copyTo%

copy %dbfPath%%headerDbf% %copyTo%
copy %dbfPath%%dedDbf% %copyTo%
