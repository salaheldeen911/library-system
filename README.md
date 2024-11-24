# Book Management API

## Authentication

- **Login**: `POST /login`  
  Body: `{ "email": "admin@email.com", "password": "1234@Abcd" }`  
  Returns: `token`

## Authors

- **Create Author**: `POST /authors` (Admin only)  
  Body: `{ "name": "Author Name", "email": "author@email.com", "password": "password" }`

## Books

- **Create Book**: `POST /books` (Author only)  
  Body: `{ "title": "Book Title", "description": "Book Description", "published_at": "2024-11-01", "bio": "Author Bio", "cover": "file" }`

- **Update Book**: `PUT /books/{id}` (Author only)

- **Export Books**: `POST /books/export` (Author only)

- **Import Books**: `POST /books/import` (Author only)

## Categories

- **Create Category**: `POST /categories` (Admin only)
