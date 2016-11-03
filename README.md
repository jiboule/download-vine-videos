# Download all your vine videos

As vine is shutting down, download all your videos to your computer with a simple command !

## Installation

Just clone the repo and install the dependencies with [Composer](https://getcomposer.org/)

    cd <repo-path>
    composer install
    
## Usage

Execute the following command in the root folder (you'll need to have PHP Cli installed)

    php vine get-videos <userId> <downloadDirectory>
    
### Exemple
    
    php vine get-videos 1234 /home/user/downloads/my-vine-videos
