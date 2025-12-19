-- Assign admin role to user
-- Replace USER_ID with your actual user ID

-- First, check your user ID
SELECT id, username, email FROM t_user LIMIT 5;

-- Then assign admin role (uncomment and replace USER_ID)
-- INSERT INTO t_auth_assignment (item_name, user_id, created_at)
-- VALUES ('admin', 'USER_ID', UNIX_TIMESTAMP())
-- ON DUPLICATE KEY UPDATE created_at = UNIX_TIMESTAMP();

-- Example: If your user ID is 1
-- INSERT INTO t_auth_assignment (item_name, user_id, created_at) VALUES ('admin', '1', UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE created_at = UNIX_TIMESTAMP();

