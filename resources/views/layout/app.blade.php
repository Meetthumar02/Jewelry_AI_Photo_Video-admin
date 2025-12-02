<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'mishruh Studio Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>

<body>

    <div class="sidebar d-flex flex-column">
        <div class="p-4 d-flex align-items-center border-bottom">
            <div class="me-2 text-center logo-bg"
                style="width: 32px; height: 32px; border-radius: 50%; font-weight: 700; line-height: 32px; font-size: 1.1rem;">
                N
            </div>
            <div>
                <div class="fs-5 fw-semibold text-dark">mishruh Studio</div>
                <div class="text-muted small">AI Jewelry Design</div>
            </div>
        </div>

        <nav class="flex-grow-1 overflow-auto">

            <div class="px-4 pt-3 pb-1 text-uppercase small text-muted fw-semibold" style="font-size: 0.7rem;">MAIN
            </div>

            <ul class="nav flex-column px-3">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('user.dashboard') ? 'active-menu' : '' }}"
                        href="{{ route('user.dashboard') }}">
                        <i class="fas fa-chart-line me-3"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>


                {{-- <hr class="my-2 mx-3 border"> --}

                {{-- <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('projects') ? 'active-menu' : '' }}"
                        href="{{ route('projects') }}">
                        <i class="fas fa-folder me-3"></i> Projects
                    </a>
                </li> --}}
            </ul>

        </nav>

        <div class="p-4 border-top">
            <div class="text-uppercase small text-muted fw-semibold mb-2" style="font-size: 0.7rem;">ACCOUNT</div>
            <a href="{{ route('logout') }}" class="nav-link text-danger py-2 px-3 d-flex align-items-center">
                <i class="fas fa-sign-out-alt me-3"></i> Sign Out
            </a>
        </div>
    </div>

    <div class="main-content">

        <nav class="navbar navbar-top">
            <div class="container-fluid justify-content-end">
                <div class="d-flex align-items-center">

                    <div class="dropdown me-2">
                        <button class="btn btn-light rounded-circle p-2" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-sun fa-lg" id="theme-icon"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="theme-toggle">
                            <li><a class="dropdown-item" href="#" data-theme="light"><i
                                        class="fas fa-sun me-2"></i> Light Mode</a></li>
                            <li><a class="dropdown-item" href="#" data-theme="dark"><i
                                        class="fas fa-moon me-2"></i> Dark Mode</a></li>
                        </ul>
                    </div>


                </div>
            </div>
        </nav>

        {{-- <main class="container-fluid p-4"> --}}
        <main class="container-fluid">
            @yield('content')
        </main>
    </div>
    <div id="profilePopup">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span>Current Plan</span>
            <button class="btn btn-sm btn-gradient">Subscribe Now</button>
        </div>

        <div class="small">Credits Used</div>

        <div class="d-flex align-items-center">
            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                <div class="progress-bar" style="width: {{ $globalProgress ?? 0 }}%;">
                </div>
            </div>

            <span class="small">
                {{ $globalUsedCredits ?? 0 }} / {{ $globalMaxCredits ?? 100 }}
            </span>
        </div>

        <div class="mt-2 small">
            Remaining Credits <b>{{ $globalCredits ?? 0 }}</b>
        </div>

        <button class="btn btn-gradient w-100 mt-3" id="openTopUpBtn">
            <i class="fa fa-plus me-1"></i> Top Up Credits
        </button>
    </div>


    <div id="topupModal" class="mishruh-modal">
        <div class="mishruh-modal-box">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-semibold">Top-Up Now</h5>
                <button class="btn btn-light p-0 modal-close" style="font-size: 20px;">×</button>
            </div>

            <div class="text-center bg-light theme-box p-2 rounded mb-3">
                <small>Available Credits</small>
                <div class="fw-bold" style="font-size: 22px;">
                    {{ $globalCredits ?? 0 }}
                </div>
            </div>

            <div class="text-center mb-2 fw-semibold">Top-up Amount</div>

            <div class="text-center mb-3">
                <span style="font-size: 30px;">₹</span>
                <input type="number" id="topupAmount" class="border-0 fw-bold"
                    style="font-size: 34px; width: 120px; text-align: center; outline: none;" value="500">
            </div>

            <div class="text-center small mb-3">
                1 ₹ = 1 Credit <br>
                You will receive <span id="receivedCredits">500</span> credits
            </div>

            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>Base Amount:</span>
                    <span id="baseAmount">₹500</span>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <span>GST (18%):</span>
                    <span id="gstAmount">₹90</span>
                </div>

                <div class="d-flex justify-content-between fw-bold border-top pt-2">
                    <span>Total Amount:</span>
                    <span id="totalAmount">₹590</span>
                </div>
            </div>

            <button id="payButton" class="btn btn-gradient w-100 mt-2">
                Pay ₹590.00
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variable to store selected plan_id
        let selectedPlanId = null;
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const themeIcon = document.getElementById('theme-icon');
            const themeLinks = document.querySelectorAll('.dropdown-item[data-theme]');

            // 1. Theme Application Function
            const setTheme = (theme) => {
                if (theme === 'dark') {
                    body.classList.add('dark-mode');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                    localStorage.setItem('theme', 'dark');
                } else {
                    body.classList.remove('dark-mode');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                    localStorage.setItem('theme', 'light');
                }
            };

            // 2. Initial Load: Default to 'light' if no preference is found
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);

            // 3. Dropdown Link Click Handler
            themeLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const newTheme = link.getAttribute('data-theme');
                    setTheme(newTheme);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function() {

            const profileBtn = document.getElementById("profileBtn");
            const profilePopup = document.getElementById("profilePopup");
            let popupOpen = false;

            profileBtn.onclick = (e) => {
                e.stopPropagation();
                popupOpen = !popupOpen;
                profilePopup.style.display = popupOpen ? "block" : "none";
            };

            // Close on outside click
            document.body.onclick = () => {
                if (popupOpen) {
                    profilePopup.style.display = "none";
                    popupOpen = false;
                }
            };
        });
        document.addEventListener("DOMContentLoaded", function() {

            // first popup button (Top Up Credits)
            const openTopUpBtn = document.getElementById("openTopUpBtn");
            const topUpTriggers = document.querySelectorAll(".trigger-topup");

            // big modal
            const topupModal = document.getElementById("topupModal");
            const closeModal = document.querySelector(".modal-close");
            const amountInput = document.getElementById("topupAmount");
            const baseAmountEl = document.getElementById("baseAmount");
            const gstAmountEl = document.getElementById("gstAmount");
            const totalAmountEl = document.getElementById("totalAmount");
            const receivedCreditsEl = document.getElementById("receivedCredits");
            const payButton = document.getElementById("payButton");

            const showTopUpModal = () => {
                topupModal.style.display = "block";
                updateAmounts();
            };

            if (openTopUpBtn) {
                openTopUpBtn.onclick = () => {
                    selectedPlanId = null; // Reset when opening manually
                    showTopUpModal();
                };
            }

            // Store plan_id when trigger-topup button is clicked
            topUpTriggers.forEach(btn => {
                btn.addEventListener("click", () => {
                    const planPrice = parseFloat(btn.dataset.price || amountInput.value || 0);
                    const planCredits = parseInt(btn.dataset.credits || planPrice, 10);
                    selectedPlanId = btn.dataset.plan || null; // Store plan_id
                    if (!isNaN(planPrice) && planPrice > 0) {
                        amountInput.value = planPrice;
                        updateAmounts(planPrice, planCredits);
                    }
                    showTopUpModal();
                });
            });

            closeModal.onclick = () => {
                topupModal.style.display = "none";
            };

            window.onclick = (e) => {
                if (e.target === topupModal) {
                    topupModal.style.display = "none";
                }
            };

            const formatCurrency = (num) => `₹${Number(num || 0).toFixed(2)}`;

            const updateAmounts = (amountVal, creditsOverride) => {
                const amount = Number(amountVal !== undefined ? amountVal : amountInput.value || 0);
                const gst = amount * 0.18;
                const total = amount + gst;
                baseAmountEl.innerText = formatCurrency(amount);
                gstAmountEl.innerText = formatCurrency(gst);
                totalAmountEl.innerText = formatCurrency(total);
                receivedCreditsEl.innerText = creditsOverride !== undefined ? creditsOverride : Math.round(
                    amount);
                if (payButton) {
                    payButton.innerText = `Pay ${formatCurrency(total)}`;
                }
            };

            updateAmounts();

            amountInput.addEventListener("input", () => {
                updateAmounts();
            });

        });

        // =============================
        //  PAY BUTTON CLICK
        // =============================

        document.getElementById("payButton").addEventListener("click", function() {

                    // Get total amount (with GST) for payment
                    let amount = parseFloat(document.getElementById("totalAmount").innerText.replace("₹", "").replace(/,/g,
                        "").trim());
                    // Get credits from the displayed value
                    let credits = parseInt(document.getElementById("receivedCredits").innerText.replace(/,/g, "").trim()) ||
                        Math.round(amount / 1.18);

                    // Validate amounts
                    if (isNaN(amount) || amount <= 0) {
                        alert("Invalid amount. Please enter a valid amount.");
                        return;
                    }

                    if (isNaN(credits) || credits <= 0) {
                        alert("Invalid credits. Please try again.");
                        return;
                    }

                    let btn = this;
                    btn.disabled = true;
                    btn.innerText = "Processing...";
    </script>
</body>

</html>
