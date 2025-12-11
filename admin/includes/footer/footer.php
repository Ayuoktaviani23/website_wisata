<footer id="footer">
    <div class="footer-content">
        <p>&copy; <?php echo date('Y'); ?> AdminHub. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
        </div>
    </div>
</footer>

<style>
#footer {
    background: var(--light);
    padding: 20px 24px;
    border-top: 1px solid var(--grey);
    margin-left: 280px;
    transition: margin-left 0.3s ease;
}

#sidebar.hide ~ #footer {
    margin-left: 60px;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--dark);
}

.footer-links {
    display: flex;
    gap: 20px;
}

.footer-links a {
    color: var(--dark-grey);
    transition: color 0.3s ease;
}

.footer-links a:hover {
    color: var(--blue);
}

@media screen and (max-width: 768px) {
    #footer {
        margin-left: 0;
        text-align: center;
    }
    
    #sidebar.hide ~ #footer {
        margin-left: 0;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 10px;
    }
}
</style>