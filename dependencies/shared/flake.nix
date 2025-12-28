{
  description = "API development dependencies";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = { self, nixpkgs, flake-utils }:
    flake-utils.lib.eachDefaultSystem (system:
      let
        pkgs = nixpkgs.legacyPackages.${system};

        php = pkgs.php82.buildEnv {
          extensions = ({ enabled, all }: enabled ++ (with all; [
            bcmath
            curl
            dom
            fileinfo
            mbstring
            pdo
            tokenizer
            xml
            zip
          ]));
          extraConfig = ''
            memory_limit = 512M
          '';
        };
      in
      {
        devShells.default = pkgs.mkShell {
          buildInputs = with pkgs; [
            php
            php82Packages.composer
            nodejs
          ];
 
          shellHook = ''
            [ -n "$FLAKE_ENV" ] && exit
            export FLAKE_ENV=1
          '';
        };
      }
    );
}
