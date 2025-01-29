-- Tạo cơ sở dữ liệu
CREATE DATABASE tugo_com;
USE tugo_com;

-- Bảng Users
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    level VARCHAR(50),
    points INT DEFAULT 0,
    profile_image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng Posts
CREATE TABLE Posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('tour', 'guide', 'company', 'photo', 'general', 'review') NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    likes INT DEFAULT 0,
    tour_name VARCHAR(255),
    start_date DATE,
    end_date DATE,
    guide_name VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng Post_Images
CREATE TABLE Post_Images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng Post_Likes
CREATE TABLE Post_Likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Bảng Comments
CREATE TABLE Comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng Vouchers
CREATE TABLE Vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount VARCHAR(255) NOT NULL,
    status ENUM('active', 'used', 'expired') NOT NULL,
    valid_until DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Đổi tên bảng Users -> users
RENAME TABLE Users TO users;

-- Đổi tên bảng Posts -> posts
RENAME TABLE Posts TO posts;

-- Đổi tên bảng Post_Images -> post_images
RENAME TABLE Post_Images TO post_images;

-- Đổi tên bảng Post_Likes -> post_likes
RENAME TABLE Post_Likes TO post_likes;

-- Đổi tên bảng Comments -> comments
RENAME TABLE Comments TO comments;

-- Đổi tên bảng Vouchers -> vouchers
RENAME TABLE Vouchers TO vouchers;
