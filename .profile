#!/bin/bash
echo "\n\n\nPreparing application\n\n\n";
sed -i 's/\$document_root/\/workspace\/public/g' /workspace/nginx.conf