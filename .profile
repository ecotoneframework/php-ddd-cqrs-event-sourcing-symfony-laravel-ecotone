#!/bin/bash
echo "-Preparing application";
sed -i 's/\$document_root/\/workspace\/public/g' /workspace/nginx.conf
#sed -i 's/\.php\$/\.php\(\/\|\$\)/g' /workspace/nginx.conf
#sed -i 's/\$fastcgi_script_name/index\.php/g' /workspace/nginx.conf