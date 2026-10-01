/**
 * Home Service Center - Client Application JS (Telegram & Special Service Edition)
 */

document.addEventListener('DOMContentLoaded', () => {
    // সার্ভিস তালিকা কনফিগারেশন (উভয় ক্ষেত্রে সবার নিচে স্পেশাল সার্ভিস সহ)
    const serviceCatalog = {
        male: [
            { 
                id: 'm_oil_body', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`, 
                name: 'ফুল বডি রিল্যাক্সেশন ও থেরাপিউটিক কেয়ার' 
            },
            { 
                id: 'm_head_back', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"/></svg>`, 
                name: 'হেড, শোল্ডার ও ব্যাক স্ট্রেস রিলিফ' 
            },
            { 
                id: 'm_deep_tissue', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/></svg>`, 
                name: 'ডিপ টিস্যু ও মাসল পেইন রিলিফ থেরাপি' 
            },
            { 
                id: 'm_physio', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>`, 
                name: 'ফিজিওথেরাপি ও পোস্ট-রিকভারি সাপোর্ট' 
            },
            { 
                id: 'm_elderly_care', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`, 
                name: 'বয়স্কদের পার্সোনাল হোম নার্সিং ও কেয়ার' 
            },
            { 
                id: 'm_special_service', 
                isSpecial: true,
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`, 
                name: 'স্পেশাল সার্ভিস (প্রিমিয়াম ভিআইপি কাস্টমাইজড কেয়ার)' 
            }
        ],
        female: [
            { 
                id: 'f_aroma_spa', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>`, 
                name: 'প্রিমিয়াম অ্যারোমাথেরাপি ও রিল্যাক্সেশন' 
            },
            { 
                id: 'f_facial_skin', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>`, 
                name: 'হারবাল ফেশিয়াল ও গ্লোয়িং স্কিন কেয়ার' 
            },
            { 
                id: 'f_pain_relief', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/></svg>`, 
                name: 'ব্যাক, লেগ ও ফুল বডি পেইন রিলিফ থেরাপি' 
            },
            { 
                id: 'f_postnatal', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>`, 
                name: 'মাতৃত্বকালীন ও প্রসবোত্তর স্পেশাল হোম কেয়ার' 
            },
            { 
                id: 'f_nursing_physio', 
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>`, 
                name: 'ফিমেল ফিজিওথেরাপি ও প্রফেশনাল নার্সিং' 
            },
            { 
                id: 'f_special_service', 
                isSpecial: true,
                iconSvg: `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`, 
                name: 'স্পেশাল সার্ভিস (প্রিমিয়াম ভিআইপি কাস্টমাইজড কেয়ার)' 
            }
        ]
    };

    let currentStep = 1;
    const totalSteps = 3;

    const stepPanes = document.querySelectorAll('.step-pane');
    const stepNodes = document.querySelectorAll('.step-node');
    const progressBarFill = document.getElementById('stepProgressFill');
    const bookingForm = document.getElementById('bookingForm');

    const customerNameInput = document.getElementById('customer_name');
    const providerGenderInputs = document.querySelectorAll('input[name="provider_gender"]');
    const customerMobileInput = document.getElementById('customer_mobile');
    const customerAgeInput = document.getElementById('customer_age');
    const serviceAddressInput = document.getElementById('service_address');
    const servicesContainer = document.getElementById('servicesListContainer');
    const gpsBtn = document.getElementById('useGpsBtn');
    const gpsStatusBadge = document.getElementById('gpsStatusBadge');
    const gpsStatusText = document.getElementById('gpsStatusText');

    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    const gpsAddressInput = document.getElementById('gps_address');

    // কনফার্মেশন মোডাল উপাদান
    const successModal = document.getElementById('successModal');
    const resRequestId = document.getElementById('resRequestId');
    const resCustomerName = document.getElementById('resCustomerName');
    const resMobile = document.getElementById('resMobile');
    const resProviderGender = document.getElementById('resProviderGender');
    const resServices = document.getElementById('resServices');
    const resDateTime = document.getElementById('resDateTime');
    const resAddress = document.getElementById('resAddress');
    const telegramBtn = document.getElementById('telegramBtn');

    function updateStep(step) {
        currentStep = step;

        stepPanes.forEach(pane => {
            pane.classList.remove('active');
            if (parseInt(pane.dataset.step) === step) {
                pane.classList.add('active');
            }
        });

        stepNodes.forEach(node => {
            const nodeStep = parseInt(node.dataset.step);
            node.classList.remove('active', 'completed');
            if (nodeStep === step) {
                node.classList.add('active');
            } else if (nodeStep < step) {
                node.classList.add('completed');
            }
        });

        const progressPercentage = ((step - 1) / (totalSteps - 1)) * 100;
        if (progressBarFill) {
            progressBarFill.style.width = `${progressPercentage}%`;
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function renderServicesForGender(gender) {
        if (!servicesContainer) return;
        const normalized = (gender === 'male' || gender === 'পুরুষ') ? 'male' : 'female';
        const services = serviceCatalog[normalized] || serviceCatalog.male;

        servicesContainer.innerHTML = '';
        services.forEach((service, index) => {
            const isSpecial = service.isSpecial === true;
            const specialBadgeHtml = isSpecial ? '<div class="special-service-badge">⭐ VIP</div>' : '';
            const specialClass = isSpecial ? 'service-item service-item-special' : 'service-item';

            const itemHtml = `
                <div class="${specialClass}">
                    <input type="checkbox" id="svc_${service.id}" name="selected_services[]" value="${service.name}" class="service-checkbox" ${index === 0 ? 'checked' : ''}>
                    <label for="svc_${service.id}" class="service-card-label">
                        ${specialBadgeHtml}
                        <div class="service-card-info">
                            <span class="service-card-icon-svg">${service.iconSvg}</span>
                            <span class="service-card-text">${service.name}</span>
                        </div>
                        <div class="service-card-check"></div>
                    </label>
                </div>
            `;
            servicesContainer.insertAdjacentHTML('beforeend', itemHtml);
        });
    }

    providerGenderInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            renderServicesForGender(e.target.value);
        });
    });

    const selectedGenderRadio = document.querySelector('input[name="provider_gender"]:checked');
    if (selectedGenderRadio) {
        renderServicesForGender(selectedGenderRadio.value);
    } else {
        renderServicesForGender('male');
    }

    const ageChips = document.querySelectorAll('input[name="quick_age"]');
    ageChips.forEach(chip => {
        chip.addEventListener('change', (e) => {
            if (e.target.checked && customerAgeInput) {
                customerAgeInput.value = e.target.value;
            }
        });
    });

    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetStep = parseInt(btn.dataset.next);

            if (currentStep === 1 && !validateStep1()) return;
            if (currentStep === 2 && !validateStep2()) return;

            updateStep(targetStep);
        });
    });

    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const targetStep = parseInt(btn.dataset.prev);
            updateStep(targetStep);
        });
    });

    function showAlert(msg, inputElement = null) {
        alert(msg);
        if (inputElement) inputElement.focus();
    }

    function validateStep1() {
        const name = customerNameInput ? customerNameInput.value.trim() : '';
        if (!name) {
            showAlert('অনুগ্রহ করে আপনার পুরো নাম লিখুন।', customerNameInput);
            return false;
        }

        const gender = document.querySelector('input[name="provider_gender"]:checked');
        if (!gender) {
            showAlert('অনুগ্রহ করে আপনি কার থেকে সেবা নিতে চান তা নির্বাচন করুন।');
            return false;
        }

        return true;
    }

    function validateStep2() {
        const mobile = customerMobileInput ? customerMobileInput.value.trim() : '';
        const cleanMobile = mobile.replace(/[-+\s]/g, '');

        if (!mobile) {
            showAlert('অনুগ্রহ করে আপনার মোবাইল নম্বরটি প্রদান করুন।', customerMobileInput);
            return false;
        }

        if (cleanMobile.length < 11 || !/^(?:\+?88|01)?\d{9,11}$/.test(cleanMobile)) {
            showAlert('অনুগ্রহ করে একটি সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন (যেমন: 01700-000000)।', customerMobileInput);
            return false;
        }

        const address = serviceAddressInput ? serviceAddressInput.value.trim() : '';
        const hasGps = latInput && latInput.value.trim() !== '';

        if (!address && !hasGps) {
            showAlert('অনুগ্রহ করে সার্ভিস নেওয়ার সম্পূর্ণ ঠিকানা লিখুন অথবা GPS লোকেশন বোতাম ব্যবহার করুন।', serviceAddressInput);
            return false;
        }

        return true;
    }

    function validateStep3() {
        const selectedServices = document.querySelectorAll('input[name="selected_services[]"]:checked');
        if (selectedServices.length === 0) {
            showAlert('অনুগ্রহ করে আপনার প্রয়োজনীয় অন্তত একটি সেবা নির্বাচন করুন।');
            return false;
        }
        return true;
    }

    // GPS লোকেশন হ্যান্ডলার
    if (gpsBtn) {
        gpsBtn.addEventListener('click', () => {
            if (!navigator.geolocation) {
                showAlert('আপনার ব্রাউজার বা ডিভাইসে GPS সুবিধা পাওয়া যাচ্ছে না। অনুগ্রহ করে ম্যানুয়ালি ঠিকানা লিখুন।');
                return;
            }

            gpsBtn.classList.add('loading');
            gpsBtn.innerHTML = '<span class="spinner"></span> লোকেশন শনাক্ত করা হচ্ছে...';

            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    if (latInput) latInput.value = lat;
                    if (lonInput) lonInput.value = lon;

                    let readableLocation = `অক্ষাংশ: ${lat.toFixed(5)}, দ্রাঘিমাংশ: ${lon.toFixed(5)}`;

                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&accept-language=bn,en`);
                        if (response.ok) {
                            const data = await response.json();
                            if (data && data.display_name) {
                                readableLocation = data.display_name;
                            }
                        }
                    } catch (err) {
                        console.log('Reverse geocoding notice:', err);
                    }

                    if (gpsAddressInput) gpsAddressInput.value = readableLocation;

                    if (serviceAddressInput && serviceAddressInput.value.trim() === '') {
                        serviceAddressInput.value = readableLocation;
                    }

                    if (gpsStatusBadge && gpsStatusText) {
                        gpsStatusText.textContent = `সফলভাবে লোকেশন যুক্ত হয়েছে: ${readableLocation.substring(0, 42)}...`;
                        gpsStatusBadge.classList.add('show');
                    }

                    gpsBtn.classList.remove('loading');
                    gpsBtn.innerHTML = '📍 লোকেশন চিহ্নিত হয়েছে (পুনরায় আপডেট করুন)';
                },
                (error) => {
                    gpsBtn.classList.remove('loading');
                    gpsBtn.innerHTML = '📍 আমার বর্তমান লোকেশন ব্যবহার করুন (GPS)';

                    let errorMsg = 'লোকেশন পাওয়া যায়নি।';
                    if (error.code === error.PERMISSION_DENIED) {
                        errorMsg = 'আপনি লোকেশন পারমিশন বাতিল করেছেন। নিচে ম্যানুয়ালি ঠিকানা লিখে দিন।';
                    } else if (error.code === error.POSITION_UNAVAILABLE) {
                        errorMsg = 'ডিভাইসের জিপিএস সংকেত পাওয়া যায়নি।';
                    }
                    showAlert(errorMsg);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        });
    }

    // ফর্ম সাবমিশন
    if (bookingForm) {
        bookingForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateStep1() || !validateStep2() || !validateStep3()) return;

            const submitBtn = document.getElementById('submitBookingBtn');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> বুকিং প্রসেস করা হচ্ছে...';
            }

            const formData = new FormData(bookingForm);

            try {
                const response = await fetch('api/book.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success && result.data) {
                    const d = result.data;
                    if (resRequestId) resRequestId.textContent = d.request_id;
                    if (resCustomerName) resCustomerName.textContent = d.customer_name;
                    if (resMobile) resMobile.textContent = d.customer_mobile;
                    if (resProviderGender) resProviderGender.textContent = d.provider_gender;
                    if (resServices) resServices.textContent = d.selected_services;
                    if (resDateTime) resDateTime.textContent = `${d.service_date} (${d.service_time})`;
                    if (resAddress) resAddress.textContent = d.service_address;

                    // টেলিগ্রাম বাটন সেটআপ
                    if (telegramBtn && d.telegram_url) {
                        telegramBtn.href = d.telegram_url;
                    }

                    if (successModal) {
                        successModal.classList.add('active');
                    }

                    bookingForm.reset();
                    updateStep(1);
                } else {
                    showAlert(result.message || 'বুকিং সম্পন্ন করতে সমস্যা হয়েছে।');
                }
            } catch (err) {
                console.error(err);
                showAlert('সার্ভারের সাথে যোগাযোগ স্থাপন করা সম্ভব হয়নি। অনুগ্রহ করে ইন্টারনেট সংযোগ চেক করুন।');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            }
        });
    }

    const closeModalBtn = document.getElementById('closeModalBtn');
    if (closeModalBtn && successModal) {
        closeModalBtn.addEventListener('click', () => {
            successModal.classList.remove('active');
        });
    }
});
