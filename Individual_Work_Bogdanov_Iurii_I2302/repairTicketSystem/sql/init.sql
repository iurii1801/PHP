-- Пользователи
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user'
);

-- Услуги (категории)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Устройства
CREATE TABLE devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- Свободные слоты
CREATE TABLE time_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slot_time DATETIME NOT NULL,
    is_booked BOOLEAN DEFAULT 0
);

-- Заявки
CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    device_id INT NOT NULL,
    problem_description TEXT NOT NULL,
    urgency ENUM('низкая', 'средняя', 'высокая') NOT NULL,
    time_slot_id INT NOT NULL,
    status ENUM('ожидание', 'подтверждено', 'отклонено') DEFAULT 'ожидание',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (device_id) REFERENCES devices(id),
    FOREIGN KEY (time_slot_id) REFERENCES time_slots(id)
);