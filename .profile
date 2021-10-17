#!/bin/bash
echo "-Preparing application";
sed -i 's/\$document_root/\/workspace\/public/g' /workspace/nginx.conf
echo 'PATH="/layers/paketo-buildpacks_php-dist/php/bin:$PATH"' >> ~/.bashrc
echo 'PATH="/layers/paketo-buildpacks_php-dist/php/bin:$PATH"' >> ~/.zshenv