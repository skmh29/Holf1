-- إدراج بيانات تجريبية للفنيين
INSERT INTO technicians (name, specialization, phone, email, status) VALUES
('أحمد محمد', 'كهرباء', '0501234567', 'ahmed@tech.com', 'available'),
('محمد علي', 'سباكة', '0509876543', 'mohammed@tech.com', 'available'),
('خالد سعيد', 'تكييف وتبريد', '0507654321', 'khaled@tech.com', 'available'),
('عبدالله حسن', 'نجارة', '0502345678', 'abdullah@tech.com', 'available'),
('فيصل أحمد', 'دهان', '0508765432', 'faisal@tech.com', 'available');

-- إدراج بعض الطلبات التجريبية
INSERT INTO maintenance_requests (request_number, name, email, phone, department, location, category, priority, description, status) VALUES
('REQ-10001', 'سارة أحمد', 'sara@example.com', '0501111111', 'الموارد البشرية', 'المبنى A - الطابق 3', 'كهرباء', 'high', 'عطل في الإضاءة في مكتب 301', 'pending'),
('REQ-10002', 'عمر خالد', 'omar@example.com', '0502222222', 'تقنية المعلومات', 'المبنى B - الطابق 2', 'تكييف', 'medium', 'المكيف لا يعمل بشكل جيد', 'in_progress'),
('REQ-10003', 'فاطمة علي', 'fatima@example.com', '0503333333', 'المالية', 'المبنى A - الطابق 1', 'سباكة', 'urgent', 'تسريب في الحمام', 'pending');

-- تعيين فني للطلب الثاني
UPDATE maintenance_requests SET assigned_to = 3 WHERE request_number = 'REQ-10002';
UPDATE technicians SET active_tasks = 1 WHERE id = 3;

-- إدراج سجل التحديثات
INSERT INTO request_history (request_id, status, notes, updated_by) VALUES
(1, 'pending', 'تم إنشاء الطلب', 'سارة أحمد'),
(2, 'pending', 'تم إنشاء الطلب', 'عمر خالد'),
(2, 'in_progress', 'تم تعيين الفني: خالد سعيد', 'admin'),
(3, 'pending', 'تم إنشاء الطلب', 'فاطمة علي');

-- تحديث الإحصائيات
UPDATE statistics SET 
    total_requests = 3,
    pending_requests = 2,
    in_progress_requests = 1,
    completed_requests = 0;
