<!-- Footer Start -->
<footer class="footer bg-light-blue text-dark py-3">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Side -->
            <div class="col-md-6 text-center text-md-left mb-2 mb-md-0">
                <p class="mb-0">&copy; {{ date('Y') }} Techspace Community Co. All Rights Reserved.</p>
            </div>
            <!-- Right Side -->
            <div class="col-md-6 text-center text-md-right">
                <p class="mb-0">Powered by - <a href="#" target="_blank" class="text-dark font-weight-bold">Techspace</a></p>
                <!-- Social Media Icons -->
                <div class="social-icons mt-2">
                    <a href="#" class="text-dark mx-2" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-dark mx-2" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-dark mx-2" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-dark mx-2" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->

<style>
    .bg-light-blue {
        background-color: #e3f2fd; /* Light blue background */
    }

    .footer {
        color: #212121; /* Dark text color */
    }

    .footer a {
        color: #212121; /* Dark link color */
        text-decoration: none; /* Remove underline */
    }

    .footer a:hover {
        color: #0d47a1; /* Darker blue on hover */
        text-decoration: underline; /* Underline on hover */
    }

    .social-icons a {
        font-size: 20px; /* Icon size */
        transition: color 0.3s; /* Smooth color transition */
    }

    .social-icons a:hover {
        color: #0d47a1; /* Darker blue color on hover */
    }

    @media (max-width: 767px) {
        .footer {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .footer p, .footer a {
            font-size: 12px;
        }

        .social-icons a {
            font-size: 16px; /* Smaller icon size for small screens */
        }
    }
</style>
