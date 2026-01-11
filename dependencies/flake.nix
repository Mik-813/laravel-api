{
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
        deps = [ 
          php
          pkgs.php82Packages.composer
          pkgs.nodejs
        ];
      in
      {
        packages.default = pkgs.buildEnv { 
          name = "php-env";
          paths = deps;
        };
        devShells.default = pkgs.mkShell {
          buildInputs = deps;
        };
      }
    );
}
