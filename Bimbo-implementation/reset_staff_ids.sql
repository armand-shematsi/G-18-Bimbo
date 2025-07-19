-- Reset staff table IDs to start from 1
DELETE FROM staff;
ALTER TABLE staff AUTO_INCREMENT = 1;
INSERT INTO staff (name, role, status) VALUES
('ISAAC', 'baker', 'Present'),
('ISCO', 'store keeper', 'Present'),
('HELEN', 'packager', 'Present'),
('ROY', 'driver', 'Present'),
('HANES', 'seller', 'Present'),
('ARMAND', 'driver', 'Present'); 