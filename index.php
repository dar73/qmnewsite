<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$page_title = 'Home';
$TITLE = SITE_NAME . ' | ' . $page_title;
$targetDate = '2024-12-03 00:00:00';
$targetTimestamp = strtotime($targetDate);
?>
<?php include '_loginBtnLogic.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'load.link.php'; ?>
    <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <meta name="description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta name="keywords" content="cleaning quotes, service providers, compare prices, house cleaning, office cleaning">
    <meta property="og:title" content="Your Cleaning Service Quotes | Quote Masters">
    <meta property="og:description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta property="og:image" content="https://thequotemasters.com/Images/logo.png">
    <meta property="og:url" content="https://thequotemasters.com/">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Quote Masters" />
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Your Cleaning Service Quotes | Quote Masters">
    <meta name="twitter:description" content="Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.">
    <meta name="twitter:image" content="https://thequotemasters.com/Images/logo.png">
    <meta name="twitter:url" content="https://thequotemasters.com/">
    <link rel="canonical" href="https://thequotemasters.com/" />
    <link rel="stylesheet" href="css/multistep_form.css">
    <script id="vtag-ai-js" async src="https://r2.leadsy.ai/tag.js" data-pid="1ENsxZC9sIH2JwtNy" data-version="062024"></script>
    <style>
        dl {
            display: block;
            margin-top: 1em;
            margin-bottom: 1em;
            margin-left: 0;
            margin-right: 0;
        }

        img {
            max-width: 100%;
            /* Ensures the image scales within the parent container */
            height: auto;
            /* Maintains the aspect ratio */
            display: block;
            /* Removes inline-block spacing */
            margin: 0 auto;
            /* Centers the image horizontally */
        }

        /* Optional: Adjust the container for better responsiveness */
        .container-fluid {
            padding: 0;
            /* Removes extra padding for a cleaner layout */
        }

        /* Optional: Add spacing or alignment for larger screens */
        @media (min-width: 768px) {
            .mt-sm-5 {
                margin-top: 3rem;
                /* Top margin adjustment for larger screens */
            }

            .mr-md-5 {
                margin-right: 3rem;
                /* Right margin adjustment for larger screens */
            }
        }

        /* Add CSS for styling */
        #countdown-container {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 20px;
        }

        #countdown {
            font-size: 24px;
            font-weight: bold;
            animation: flash 1s infinite;
        }

        .offer-text {
            font-size: 18px;
            margin-right: 10px;
        }

        /* Flashing effect */
        @keyframes flash {

            0%,
            50%,
            100% {
                opacity: 1;
            }

            25%,
            75% {
                opacity: 0;
            }
        }

        .faq-section {
            background-color: #BE1E2D;
            color: #fff;
            padding: 40px 20px;
            border-radius: 8px;
            font-family: 'Arial', sans-serif;
        }

        .faq-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .faq-item {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 6px;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .faq-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .faq-question {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #6D6E71;
        }

        .faq-answer {
            display: none;
            font-size: 1rem;
        }

        .faq-answer ul {
            margin-top: 10px;
            padding-left: 20px;
        }

        .faq-answer ul li {
            margin-bottom: 10px;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        a {
            color: #BE1E2D;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Organization",
            "name": "Quote Masters",
            "description": "Get instant cleaning service quotes from top providers. Compare prices and choose the best option for your needs.",
            "url": "https://thequotemasters.com/",
            "logo": "https://thequotemasters.com/Images/logo.png",
            "alternateName": "The Quote Masters",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Florida limited liability company",
                "addressLocality": "Southeastern region",
                "addressRegion": "Southeastern region ",
                "postalCode": "32013",
                "addressCountry": "US"
            },

            "areaServed": {
                "@type": "State",
                "name": "Florida"
            },
            "contactPoint": [{
                "@type": "ContactPoint",
                "telephone": "(866)958-8773",
                "contactType": "customer service"

            }]
        }
    </script>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include 'header.php'; ?>
        <!-- /.navbar -->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- logo banner -->
            <div class="logo_banner">
                <div class="container-fluid">
                    <div class="row justify-content-lg-end">
                        <div class="col col-lg-8 col-12 text-right">
                            <img class="logo_banner_image mt-sm-5 mt-3 mr-md-5" src="Images/logo.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Header (Page header) -->
            <div class="content-header quote_header">
                <div class="container">
                    <div class="row pb-5 no-gutters slider-text js-fullheight align-items-center justify-content-center" id="get_quote_sec" data-scrollax-parent="true">
                        <div class="col-md-12 ftco-animate">
                            <!-- <h6 class="subheading">We Will Make Your Place As Good As New</h6> -->
                            <h1 class="mb-4">NEED CLEANING QUOTES FOR JANITORIAL SERVICE?</h1>
                        </div>
                        <div class="col-md-12">
                            <div class="wrap-appointment d-md-flex pb-5 pb-md-0">
                                <form action="#" autocomplete="off" class="appointment w-100">
                                    <div class="row justify-content-center">
                                        <div class="col-md-8 d-flex align-items-center pt-4 pt-md-0">
                                            <div class="col-12 form-group px-4 px-md-0">
                                                <input type="text" class="form-control zip_form" id="zipcode" placeholder="Enter zip code where quotes are needed" autocomplete="off">
                                                <!-- floating ginnie -->
                                                <div class="hide_ginnie col-lg-4">
                                                    <div class="card">
                                                        <div class="card-body d-flex">
                                                            <div class="col-5 my-auto">
                                                                <img src="Images/prati/guide_ginnie.png" alt="">
                                                            </div>
                                                            <div class="col-7 my-auto">
                                                                <h6 class="card-title">Let's Start!!</h6>
                                                                <p class="card-title">Please enter the zip code where service is required</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- floating ginnie -->
                                                <input type="hidden" id="zipid">
                                                <!-- search container class start -->
                                                <div class="search_cont">
                                                    <div class="card p-0 m-0 rounded-0">
                                                        <div class="list-group list-group-item-action" id="content">

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- search container class ends -->

                                            </div>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-center">
                                            <div class="form-group px-4 px-md-0 d-flex">
                                                <!-- <input type="button" id="btn_search" value="Get a Quote" class="btn py-3 px-4"> -->
                                                <button type="button" id="btn_search" class="btn btn-block">GET FREE QUOTES</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->

            <div class="content">
                <div class="container py-3">
                    <h2 class="section_heading text-center">Popular Services</h2>
                    <div class="row justify-content-center my-5">

                        <div class="col-md-3 col-sm-4 text-center card m-3 services_card">
                            <div class="services_img_div1"></div>
                            <h4 class="float-none pt-3">Janitorial</h4>
                            <div class="service_card_overlay">
                                <div class="overlay_text">
                                    <h4>Commercial Office Cleaning </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-4 text-center card m-3 services_card">
                            <div class="ribbon-2">Coming Soon</div>
                            <div class="services_img_div2"></div>
                            <h4 class="float-none pt-3">Upcoming Services</h4>
                            <div class="service_card_overlay">
                                <div class="overlay_text">
                                    <h4>Electrical</h4>
                                    <h4>Plumbing</h4>
                                    <h4>Painting</h4>
                                    <h4>Home Improvements</h4>
                                    <h4>Lawn Maintenance</h4>
                                </div>
                            </div>
                        </div>

                    </div>
                    <h2 class="section_heading text-center">Why Choose Us</h2>
                    <div class="row justify-content-center my-5">
                        <div class="col-md-8 text-center  p-4">
                            <div class="embed-responsive embed-responsive-16by9 mb-4">
                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SFTj7wn3Xvs?loop=1&controls=0&playlist=SFTj7wn3Xvs" allowfullscreen></iframe>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> 100% verified leads of local customers with "desire to meet (and engage)"</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Unique messaging system generating codes which ensures all meeting requests are genuine</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Appointments with specific day and time to meet with the customer</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Initiating calendar invitations as reminders coupled with 3-step follow up process to ensure minimal "no-shows"</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Company details shared with the customers prior to appointments so they are aware of your company benefits</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Simple lead purchase experience along with specific credit guidelines for cancelled appointments</li>
                                <li class="mb-3"><i class="fas fa-check-circle text-danger"></i> Gold Standard QM Support Team providing Phone & Chat Support Services</li>
                            </ul>
                        </div>
                    </div>

                    <!-- review -->
                    <div class="container homepage_review py-5 border-top">
                        <h1 class="text-center">WHAT CUSTOMERS SAY ABOUT OUR SERVICES</h1>

                        <div class="row justify-content-center my-5">
                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">My goodness the website was so easy to use! I think it took me about 2 minutes to set up meetings for 5 companies. The meeting all happened as scheduled and I like the quality of cleaners I have to choose from!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #6D6E71;">M</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Mureen Parker</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@parker...7 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">Quote Masters is different than other services I have used like them. They sent me great companies and it was hard to pick. Then they followed through to make sure all went well and if I found what I was looking for. Very refreshing that I never felt on my own!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #00AEEF;">T</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Tony Brown</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@brown...1 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">I must say seldom do you find a service like this who matches you up with qualified providers and lets you get to know them before they ever visit your office. It made the process of a new provider so EASY! Thanks Quote Masters for doing all the leg work!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #EF4136;">R</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Robert Kim</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@kim...3 months ago</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="star_rating mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <!-- <span class="fas fa-star-half-alt active-star"></span>   -->
                                </div>
                                <p class="homepage_review_para">Website was clear and easy to navigate. The QM customer service answered quickly and walked me through the only question I had. I will use these guys again!</p>
                                <div class="d-flex">
                                    <div class="review_profile" style="background-color: #92278F;">S</div>
                                    <div class="ml-2">
                                        <h6 style="font-weight: 500; font-size: 15px; margin-bottom: 0px;">Scott Lars</h6>
                                        <p style="font-size: 13px; font-weight: 300;">@lars...5 months ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="faq-section">
                        <h2 class="faq-title">Frequently Asked Questions</h2>

                        <div class="faq-item">
                            <div class="faq-question">1. Why Businesses Use Janitorial Companies for Their Offices</div>
                            <div class="faq-answer">
                                <p>Maintaining a clean and sanitized office environment is crucial for productivity and employee well-being. Professional janitorial companies offer several advantages:</p>
                                <ul>
                                    <li>
                                        <strong>Expertise in Cleaning Standards:</strong> Trained professionals adhere to industry standards and use advanced techniques to clean effectively.
                                        <a href="https://momcleaning.com/11-benefits-of-a-professional-cleaning-service-why-every-business-needs-one" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Consistency and Reliability:</strong> Outsourcing ensures consistent schedules and thorough cleaning without burdening internal staff.
                                        <a href="https://www.coit.com/blog/your-business/10-benefits-commercial-cleaning-service-why-your-business-needs-one" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Employee Morale and Productivity:</strong> A clean workspace improves focus and reduces stress, fostering a positive work atmosphere.
                                        <a href="https://dynastycommercialcleaning.com/blog/benefits-of-commercial-cleaning-services" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">2. The Benefits of Professional Cleaning Services vs. Doing It Yourself</div>
                            <div class="faq-answer">
                                <p>While it might seem cost-effective to handle cleaning in-house, professional services provide value in ways that DIY approaches cannot:</p>
                                <ul>
                                    <li>
                                        <strong>Access to Advanced Tools:</strong> Professionals use high-grade equipment for tasks like floor buffing and carpet shampooing.
                                        <a href="https://momcleaning.com/11-benefits-of-a-professional-cleaning-service-why-every-business-needs-one" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Time Efficiency:</strong> Skilled cleaners complete tasks faster and more thoroughly, freeing up employees to focus on their core roles.
                                        <a href="https://cleansolutionllc.com/blog/5-benefits-of-hiring-professional-janitorial-company" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Health and Safety:</strong> Professionals use certified disinfectants that reduce allergens, bacteria, and viruses, creating a healthier workspace.
                                        <a href="https://talbotforce.com/blog/5-benefits-of-janitorial-services" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">3. Efficiency Analysis: Why Outsourcing Janitorial Services Makes Sense</div>
                            <div class="faq-answer">
                                <p>Outsourcing janitorial services can significantly reduce overhead costs and improve operational efficiency. Here’s why:</p>
                                <ul>
                                    <li>
                                        <strong>Cost Savings:</strong> Avoid expenses like cleaning supplies, equipment maintenance, and training for in-house staff.
                                        <a href="https://www.burgoscleaning.com/cost-benefit-analysis-in-house-vs-outsourced-cleaning-services" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Flexibility:</strong> Adjust cleaning schedules based on your business's changing needs without long-term commitments.
                                        <a href="https://blog.pegasusclean.com/in-house-vs.-outsourcing-facility-cleaning" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Focus on Core Business Activities:</strong> Delegating cleaning tasks allows your employees to concentrate on their primary responsibilities.
                                        <a href="https://ccsbts.com/the-top-5-benefits-of-professional-janitorial-services" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">4. What to Expect from a Janitorial Quote</div>
                            <div class="faq-answer">
                                <p>When requesting a janitorial quote, it’s essential to understand what’s included:</p>
                                <ul>
                                    <li>
                                        <strong>Detailed Pricing Breakdown:</strong> This should cover labor, supplies, and any additional services.
                                        <a href="https://www.vanguardsv.com/2024/02/cost-comparison-in-house-cleaning-vs-hiring-janitorial-services" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Scope of Work:</strong> A clear outline of tasks like trash removal, restroom sanitation, or floor care.
                                        <a href="https://excellencedeepclean.com/insights/janitorial-cleaning-services-top-benefits-for-your-company-or-office" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Customization Options:</strong> Many providers tailor their services based on specific business needs.
                                        <a href="https://www.maintenance-one.com/7-key-benefits-of-hiring-a-cleaning-service" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>



                        <div class="faq-item">
                            <div class="faq-question">5. You Booked Your Janitorial Quote—What’s Next?</div>
                            <div class="faq-answer">
                                <p>Congratulations on booking a janitorial quote! Here’s how to make the most of your walkthrough:</p>
                                <ul>
                                    <li>
                                        <strong>Preparation:</strong> Ensure access to all areas for assessment and gather your cleaning priority list.
                                        <a href="https://mediawirehub.com/the-benefits-of-professional-office-cleaning-services" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Ask Questions:</strong> Discuss experience, certifications, and flexibility in scheduling.
                                        <a href="https://daythroughnightcleaning.com/outsourcing-janitorial-services-vs-in-house-cleaning-staff-whats-right-for-your-business" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Review the Proposal:</strong> After the walkthrough, carefully examine the proposal to ensure it aligns with your needs.
                                        <a href="https://www.akbuildingservices.com/blog/the-benefits-of-hiring-janitorial-services-for-your-business" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">6. Top 5 Questions to Ask a Janitorial Service Provider</div>
                            <div class="faq-answer">
                                <p>Before hiring a janitorial company, ask these questions to ensure a good fit:</p>
                                <ul>
                                    <li>
                                        <strong>What is your experience with businesses similar to mine?</strong>
                                        <a href="https://www.lacostaservices.com/in-house-vs-outsourced-cleaning" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Are your staff trained and background-checked?</strong>
                                        <a href="https://cleansolutionllc.com/blog/5-benefits-of-hiring-professional-janitorial-company" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>What cleaning products and equipment do you use?</strong>
                                        <a href="https://1stonesolutions.com/outsourcing-vs-in-house-cleaning" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>What is included in your service packages?</strong>
                                        <a href="https://dynastycommercialcleaning.com/blog/benefits-of-commercial-cleaning-services" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>What’s your policy on missed or unsatisfactory services?</strong>
                                        <a href="https://cleansolutionllc.com/blog/outsourcing-janitorial-vs-in-house" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">7. The Hidden Costs of In-House Cleaning Teams</div>
                            <div class="faq-answer">
                                <p>Hiring in-house cleaning staff might seem economical but comes with hidden costs:</p>
                                <ul>
                                    <li>
                                        <strong>Training and Supervision:</strong> New hires require regular training and monitoring.
                                        <a href="https://www.bcsfacilities.com/post/the-in-source-vs-outsource-dilemma-a-case-study-on-the-hidden-costs-of-in-house-janitorial-teams" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Equipment Maintenance:</strong> Purchasing and maintaining cleaning equipment can be expensive.
                                        <a href="https://www.vanguardsv.com/2024/02/cost-comparison-in-house-cleaning-vs-hiring-janitorial-services" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Liability Risks:</strong> Employees may file claims for injuries sustained during cleaning tasks.
                                        <a href="https://escfederal.com/outsourcing-janitorial-services-pros-cons" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">8. Eco-Friendly Janitorial Services: Why They Matter</div>
                            <div class="faq-answer">
                                <p>Eco-friendly cleaning services benefit both businesses and the planet:</p>
                                <ul>
                                    <li>
                                        <strong>Improved Air Quality:</strong> Green cleaning reduces toxins in the air, creating a healthier workspace.
                                        <a href="https://www.greenseal.org" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Cost Savings:</strong> Energy-efficient equipment and reusable supplies lower long-term expenses.
                                        <a href="https://usgbc.org" target="_blank">Source</a>
                                    </li>
                                    <li>
                                        <strong>Sustainability Goals:</strong> Companies can align with environmental regulations and improve their public image.
                                        <a href="https://www.cleanlink.com" target="_blank">Source</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 class="faq-title">Q&A for Service Providers Considering Joining Quote Masters Platinum</h2>

                        <div class="faq-item">
                            <div class="faq-question">How does Quote Masters help me grow my business?</div>
                            <div class="faq-answer">
                                <p>Quote Masters streamlines your lead generation and appointment booking process, allowing you to focus on providing exceptional services. With our Platinum program, you receive appointments scheduled according to your preferences, ensuring that your time is spent with potential clients genuinely interested in your services. This efficient approach minimizes administrative burdens and maximizes your opportunities for growth.</p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">What makes Quote Masters different from other lead companies?</div>
                            <div class="faq-answer">
                                <p>Unlike traditional lead generation companies that sell unverified contact information, Quote Masters takes a proactive approach by managing the entire appointment booking process. Customers choose how many providers they want to meet, and we ensure those appointments align with your availability and preferences. Our platform is designed to save you time by reducing no-shows and delivering confirmed appointments with decision-makers.</p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">Why should I choose Quote Masters over handling leads myself?</div>
                            <div class="faq-answer">
                                <p>Managing leads in-house often requires significant time and resources for follow-ups, scheduling, and confirmation. With Quote Masters, you don’t have to worry about these tasks. Our system synchronizes with your calendar and schedules appointments based on your availability, ensuring that every meeting fits seamlessly into your schedule. This allows you to focus on delivering exceptional service instead of managing logistics.</p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">How does Quote Masters improve appointment quality?</div>
                            <div class="faq-answer">
                                <p>We vet customers during the booking process to ensure they are genuinely interested in receiving services. Customers can customize their requests to specify the number of providers they want to meet and the time frames for those appointments. This ensures that the appointments we book for you are meaningful and aligned with customer intent, increasing your chances of securing new business.</p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">What are the benefits of the Platinum program for service providers?</div>
                            <div class="faq-answer">
                                <p>The Platinum program offers premium features, including:</br>
                                    Customizable Preferences: Set your service areas, industry exclusions, and availability to match your business needs.</br>
                                    Automated Scheduling: Appointments are scheduled directly into your calendar, reducing the risk of missed opportunities.</br>
                                    Priority Support: Enjoy faster credit reviews and dedicated customer service to address any issues quickly.</br>
                                    Reduced Administrative Work: Our system handles appointment confirmations and updates, allowing you to focus on service delivery.</br>

                                </p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">Does Quote Masters charge for missed or canceled appointments?</div>
                            <div class="faq-answer">
                                <p>Our system is designed to minimize missed or canceled appointments by ensuring customers confirm their schedules in advance. If appointments are missed due to your unavailability or outdated calendar settings, they may not be eligible for credit. However, appointments canceled by customers or rescheduled beyond your availability window will be reviewed promptly. Transactions are processed 24 hours after your scheduled appointment to give you time to submit a credit request.

                                </p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">Can I customize the types of clients I receive?</div>
                            <div class="faq-answer">
                                <p>Yes! As a Platinum member, you have full control over your preferences, including:</br>

                                    Service Area: Define the zip codes or regions you want to serve.</br>
                                    Industry Exclusions: Specify industries you do not wish to work with.</br>
                                    Schedule Parameters: Block out unavailable days or hours, and sync your calendar to avoid conflicts.</br>
                                    These preferences ensure that your time is spent with clients who fit your ideal business profile.</br>
                                </p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">Why does Quote Masters only offer the Platinum program now?</div>
                            <div class="faq-answer">
                                <p>We’ve transitioned exclusively to the Platinum program to provide a superior experience for service providers and customers alike. By focusing on Platinum, we ensure a higher level of service, better appointment quality, and more tailored features that drive meaningful business growth. This shift allows us to dedicate more resources to supporting your success.
                                </p>

                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question">How does Quote Masters support my business success?</div>
                            <div class="faq-answer">
                                <p>We don’t just provide leads—we act as your booking partner. Our system simplifies the entire process, from customer inquiry to confirmed appointment, giving you a steady stream of opportunities to grow your business. With upgraded tools, advanced scheduling options, and robust support, we make it easy for you to stay focused on delivering top-tier service while we handle the details.
                                    Will add these today.
                                </p>

                            </div>
                        </div>


                        <!-- Repeat for other FAQ items -->
                    </div>


                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- <div class="welcome_gennie">
            <img src="Images/prati/welcom_ginnie.gif" alt="">
        </div> -->
        <div class="popup" style="display: none;">
            <div class="quote_btn" onclick="myFunction()"><img class="my-float" src="Images/prati/chat.png" alt=""></div>
            <!-- floating ginnie -->
            <div class="hide_ginnie" style="min-width: 200px;">
                <div class="card">
                    <div class="card-body d-flex">
                        <div class="col-6 my-auto">
                            <img class="card-img" src="Images/prati/guide_ginnie.png" alt="">
                        </div>
                        <div class="col-6 my-auto">
                            <p class="card-text">Hello! Ask me anything!</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- floating ginnie -->
            <div class="popuptext" id="myPopup">
                <div class="row justify-content-md-center">
                    <div>
                        <!--start code-->
                        <div class="card">
                            <div class="chat-logoimage-bg">
                                <div class="card-header chat-card-header p-0">
                                    <div class="p-2 chat-card-header-img">
                                        <img src="Images/prati/chat.png" alt="">
                                    </div>
                                    <h6>Chat Support</h6>
                                    <div style="margin-left: auto; margin-right: 5px;">
                                        <i class="fa fa-minus m-1 min-chat-bot" aria-hidden="true"></i>
                                        <i class="fa fa-times m-1 close-chat-bot" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="card-body messages-box">
                                    <ul class="list-unstyled messages-list">


                                    </ul>
                                </div>
                                <div class="card-header message-input-send">
                                    <div class="input-group type-message-input">
                                        <input id="input-me" type="text" name="messages" class="form-control input-sm" placeholder="Type message here..." />
                                        <span class="input-group-append input-send-btn">
                                            <button type="button" class="btn" value="Send" onclick="send_msg()"><img src="Images/prati/message-send.png" alt=""></button>
                                            <!-- <input type="button" class="btn btn-primary" value="Send" onclick="send_msg()"> -->
                                        </span>
                                    </div>
                                    <p class="guide_para">Click the send button to respond</p>
                                </div>
                            </div>
                        </div>
                        <!--end code-->
                    </div>
                </div>
            </div>

        </div>


        <!-- <input type="checkbox" id="check"> 
    <label class="chat-btn" for="check"> 
        <i class="fa fa-commenting-o comment"></i> 
        <i class="fa fa-close close"></i> 
    </label> -->


        <?php include 'footer.php'; ?>
    </div>
    <?php include 'load.scripts.php'; ?>
    <script src="https://www.google.com/recaptcha/api.js?render=6Lf_pJInAAAAANtUgwkJ4V3unOz3SCzP-NENNz-M"></script>

    <script>
        // When the user clicks on div, open the popup
        function myFunction() {
            var popup = document.getElementById("myPopup");
            popup.classList.toggle("show");

        }

        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('active');
            });
        });


        function getCurrentTime() {
            var now = new Date();
            var hh = now.getHours();
            var min = now.getMinutes();
            var ampm = (hh >= 12) ? 'PM' : 'AM';
            hh = hh % 12;
            hh = hh ? hh : 12;
            hh = hh < 10 ? '0' + hh : hh;
            min = min < 10 ? '0' + min : min;
            var time = hh + ":" + min + " " + ampm;
            return time;
        }

        function send_msg() {
            jQuery('.start_chat').hide();
            var txt = jQuery('#input-me').val();
            var html = '<li class="messages-me clearfix"><span class="message-img"><img src="Images/prati/user_avatar.png" class="avatar-sm rounded-circle"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Me</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p message-p-right">' + txt + '</p></div></li>';
            jQuery('.messages-list').append(html);
            jQuery('#input-me').val('');
            if (txt) {
                jQuery.ajax({
                    url: 'get_bot_message.php',
                    type: 'post',
                    data: 'txt=' + txt,
                    success: function(result) {
                        var html = '<li class="messages-you clearfix"><span class="message-img"><img src="Images/prati/q_avatar.png" class="avatar-sm"></span><div class="message-body clearfix"><div class="message-header"><strong class="messages-title">Ginnie</strong> <small class="time-messages text-muted"><span class="fas fa-time"></span> <span class="minutes">' + getCurrentTime() + '</span></small> </div><p class="messages-p message-p-left">' + result + '</p></div></li>';
                        jQuery('.messages-list').append(html);
                        jQuery('.messages-box').scrollTop(jQuery('.messages-box')[0].scrollHeight);
                    }
                });
            }
        }

        $(document).ready(function() {
            $("#zipcode").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    //console.log(request);
                    $.ajax({
                        url: 'api/search.php',
                        method: 'POST',
                        data: {
                            search: request.term
                        },
                        success: function(res) {
                            //console.log(res);
                            response($.map(res, function(item) {
                                return {
                                    label: item.label, // Displayed in the autocomplete suggestions
                                    value: item.label, // Displayed in the input field after selection
                                    id: item.value // 
                                };
                            }));
                            //$('#content').html(res);

                        }
                    });

                },
                select: function(event, ui) {
                    //console.log(ui.item.label);
                    // Set selection
                    //arr = ui.item.label.split("|");
                    // display the selected text
                    $('#zipcode').val(ui.item.label); // save selected id to input
                    $('#areaid').val(ui.item.id); // save selected id to input
                    $('#zipid').val(ui.item.id); // save selected id to input
                    //$('#txtcustid').val(ui.item.id);
                    //window.location.href="choose_items.php?txtcustid="+ui.item.id;
                },
            });


            const form = document.getElementById('regForm');
            // $(document).on('keyup', '#zipcode', function() {
            //     let search = $('#zipcode').val();
            //     if (search != "") {
            //         $.ajax({
            //             url: 'api/search.php',
            //             method: 'POST',
            //             data: {
            //                 search: search
            //             },
            //             success: function(res) {
            //                 //console.log(res);
            //                 $('#content').html(res);

            //             }
            //         });

            //     }

            // });

            $(document).on('click', '#btn_search,#Btn_icon', function() {
                let search = $('#zipid').val();
                //let search = $('#zipcode').val();
                $('#areaid').val(search);
                if (search != "") {
                    $('#GetQ_modal').modal('show');
                    //$('#service_modal').modal('show');


                    //$('#customer_regsiter_modal').modal('toggle');
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please select your ZIP code from dropdown'
                    })
                    $('#zipcode').focus();
                }

            });

            $(document).on('click', 'a', function() {
                // $('#zipcode').val($(this).text());
                // $('#content').html('');
                // $('#zipid').val($(this).data("id"));
            });

            $(document).on('click', '#confirm_booking', function() {
                //$('#exampleModal').modal('toggle');
                let search = $('#zipid').val();
                $('#areaid').val(search);
                if (search != "") {
                    //$('#confirm_modal').modal('toggle');
                    let text = "We are going to send a verification code to the email address and cell phone number you have provided";
                    if (!$('#custAgreeTerms').is(':checked')) {
                        alert('Please agree to the terms.');
                        return false;
                    } else {
                        if (confirm(text) == true) {
                            text = "You pressed OK!";
                            //console.log(text);
                            grecaptcha.ready(function() {
                                grecaptcha.execute('6Lf_pJInAAAAANtUgwkJ4V3unOz3SCzP-NENNz-M', {
                                    action: 'submit'
                                }).then(function(token) {
                                    // Append the reCAPTCHA token to the form as a hidden input
                                    if ($('#regForm input[name="g-recaptcha-response"]').length === 0) {
                                        $('<input>').attr({
                                            type: 'hidden',
                                            name: 'g-recaptcha-response',
                                            value: token
                                        }).appendTo('#regForm');
                                    } else {
                                        $('#regForm input[name="g-recaptcha-response"]').val(token);
                                    }
                                    // Add your logic to submit to your backend server here.
                                    form.submit();
                                });
                            });
                        } else {
                            text = "You canceled!";
                        }
                    }
                    // form.submit();


                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please search for your zipcode'
                    })
                }

            });

            //chatbot close button
            $(".close-chat-bot").on("click", function() {
                $("#myPopup").removeClass("show");
            });
            $(".min-chat-bot").on("click", function() {
                var mesgBoxElement = $(".messages-box");
                var mesgInputElement = $(".message-input-send");
                // Toggle the "active" class on the target element
                mesgBoxElement.toggleClass("hide");
                mesgInputElement.toggleClass("hide");
            });
        });
    </script>

    <?php include '_getQuotes_modal.php'; ?>
</body>

</html>
<?php sql_close(); ?>