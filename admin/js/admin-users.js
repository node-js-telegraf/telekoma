/* ============================================
   KILLBYTE ADMIN - USERS MODULE
   Admin user management
   ============================================ */

const adminUsers = {
    users: [],

    init() {
        this.loadUsers();
        console.log('[AdminUsers] Initialized');
    },

    loadUsers() {
        this.users = AdminCore.getStorage('kb_admin_users', [
            { username: 'admin', password: 'killbyte1337', role: 'superadmin', name: 'Administrator', createdAt: new Date().toISOString() }
        ]);
        this.renderUsers();
    },

    saveUsers() {
        AdminCore.setStorage('kb_admin_users', this.users);
        this.renderUsers();
    },

    renderUsers() {
        const container = document.getElementById('usersList');
        if (!container) return;

        container.innerHTML = this.users.map(user => `
            <div class="plan-item">
                <div class="plan-item-info">
                    <div class="plan-item-name">
                        <i class="fas fa-user${user.role === 'superadmin' ? '-shield' : ''}"></i>
                        ${AdminCore.escapeHtml(user.name || user.username)}
                    </div>
                    <div class="plan-item-price">
                        @${user.username} • ${user.role}
                    </div>
                </div>
                <div class="plan-item-actions">
                    ${user.username !== 'admin' ? `
                        <button onclick="adminUsers.editUser('${user.username}')" class="plan-item-btn">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="adminUsers.deleteUser('${user.username}')" class="plan-item-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : '<span class="badge" style="background: var(--success);">Protected</span>'}
                </div>
            </div>
        `).join('');
    },

    openAddModal() {
        const name = prompt('Enter user name:');
        if (!name) return;

        const username = prompt('Enter username:');
        if (!username) return;

        const password = prompt('Enter password:');
        if (!password) return;

        const role = confirm('Make this user a superadmin?') ? 'superadmin' : 'admin';

        const result = adminAuth.addUser(username, password, name, role);
        if (result.success) {
            this.loadUsers();
            AdminCore.showToast(result.message, 'success');
        } else {
            AdminCore.showToast(result.message, 'error');
        }
    },

    editUser(username) {
        const user = this.users.find(u => u.username === username);
        if (!user) return;

        const newName = prompt('Enter new name:', user.name);
        if (newName) user.name = newName;

        const newRole = confirm('Make superadmin?') ? 'superadmin' : 'admin';
        user.role = newRole;

        const newPassword = prompt('Enter new password (leave empty to keep current):');
        if (newPassword) user.password = newPassword;

        user.updatedAt = new Date().toISOString();
        this.saveUsers();
        AdminCore.showToast('User updated', 'success');
    },

    deleteUser(username) {
        const result = adminAuth.removeUser(username);
        if (result.success) {
            this.loadUsers();
            AdminCore.showToast(result.message, 'success');
        } else {
            AdminCore.showToast(result.message, 'error');
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    adminUsers.init();
});

window.adminUsers = adminUsers;
