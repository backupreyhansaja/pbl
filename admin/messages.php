<?php
require_once 'includes/auth.php';
require_once '../config/database.php';

$pageTitle = 'Pesan Masuk';
$db = new Database();

$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($db->query("DELETE FROM contact_messages WHERE id = $id")) {
        $success = 'Pesan berhasil dihapus!';
    }
}

// Handle mark as read
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    $db->query("UPDATE contact_messages SET is_read = TRUE WHERE id = $id");
}

// Fetch all messages
$result = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$data = $db->fetchAll($result);

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-green-700"><?php echo $success; ?></p>
        </div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <?php if ($data && count($data) > 0): ?>
        <div class="divide-y divide-gray-200">
            <?php foreach ($data as $msg): ?>
                <div class="p-6 hover:bg-gray-50 transition <?php echo !$msg['is_read'] ? 'bg-blue-50' : ''; ?>">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 text-lg flex items-center">
                                    <?php echo htmlspecialchars($msg['name']); ?>
                                    <?php if (!$msg['is_read']): ?>
                                        <span class="ml-2 px-2 py-1 bg-red-500 text-white text-xs rounded-full">Baru</span>
                                    <?php endif; ?>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-envelope mr-1"></i><?php echo htmlspecialchars($msg['email']); ?>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-clock mr-1"></i><?php echo date('d M Y, H:i', strtotime($msg['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($msg['subject']): ?>
                        <div class="mb-3">
                            <p class="font-semibold text-gray-700">
                                <i class="fas fa-tag mr-2"></i>Subjek: <?php echo htmlspecialchars($msg['subject']); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-gray-100 rounded-lg p-4 mb-4">
                        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></p>
                    </div>
                    
                    <div class="flex space-x-2">
                        <?php if (!$msg['is_read']): ?>
                            <a href="?read=<?php echo $msg['id']; ?>" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-sm">
                                <i class="fas fa-check mr-1"></i>Tandai Sudah Dibaca
                            </a>
                        <?php endif; ?>
                        <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                            <i class="fas fa-reply mr-1"></i>Balas Email
                        </a>
                        <a href="?delete=<?php echo $msg['id']; ?>" onclick="return confirmDelete('Apakah Anda yakin ingin menghapus pesan ini?')" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-inbox text-6xl mb-4"></i>
            <p class="text-lg">Belum ada pesan masuk</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
