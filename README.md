# Bartleby Web Scraper

## Overview
This script is designed to scrape data from the Bartleby website, specifically retrieving question-and-answer pairs and solution answers. It interacts with the Bartleby API endpoints to fetch the necessary information and generates HTML files containing the scraped content.

## Features
- Scrapes question-and-answer pairs from Bartleby's Q&A section.
- Scrapes solution answers from Bartleby's solution section.
- Retrieves like and dislike counts for questions.
- Handles authentication using access tokens.
- Generates HTML files with the scraped content for easy viewing.

## Requirements
- PHP environment
- cURL extension enabled

## Usage
1. Ensure PHP and cURL are installed and enabled in your environment.
2. Set up a web server to run the PHP script.
3. Navigate to the script's URL with the required parameters:
    - For Q&A scraping: `script_url?url=<question_url>`
    - For solution scraping: `script_url?url=<solution_url>`
4. The script will output the HTML content containing the scraped data.

## Notes
- Make sure to have proper authorization tokens for accessing Bartleby's API endpoints.
- Ensure compliance with Bartleby's terms of service and API usage policies.

## Disclaimer
This script is provided for educational purposes only. Use it responsibly and respect the terms of service of the websites you scrape.
