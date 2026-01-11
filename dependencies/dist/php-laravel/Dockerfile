FROM nixos/nix:2.32.4 AS builder

RUN mkdir -p /etc/nix \
 && echo "experimental-features = nix-command flakes" >> /etc/nix/nix.conf

COPY flake.* /deps-shared/

RUN nix profile add path:/deps-shared

ENTRYPOINT [ "/workspace/dependencies/dist/php-laravel/entrypoint.sh" ]
