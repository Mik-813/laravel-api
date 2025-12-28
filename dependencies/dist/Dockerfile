FROM nixos/nix:2.32.4

RUN mkdir -p /etc/nix \
 && echo "experimental-features = nix-command flakes" >> /etc/nix/nix.conf

WORKDIR /workspace
ENTRYPOINT [ "dependencies/dist/entrypoint.sh" ]