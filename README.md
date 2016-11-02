# Download all your vine videos

As vine is shutting down, download all your videos to your computer with a simple command !

## Installation

Just clone the repo and install the composer dependencies

    composer install
    
## Usage

In a terminal with php cli :

    cd download-vine-videos
    php vine get-videos <userId> <downloadDirectory>
    
### Exemple
    
    php vine get-videos 1234 /home/user/downloads/my-vine-videos
