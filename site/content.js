/**
 * Everything on the page that a person might want to change lives in this
 * one file. No database, no admin: edit, save, redeploy.
 *
 * ── The videos ─────────────────────────────────────────────────────
 *
 * Each entry's `src` can be ANY ONE of:
 *
 *   YouTube link      'https://www.youtube.com/watch?v=XXXXXXXXXXX'  or  'https://youtu.be/XXXXXXXXXXX'
 *   Vimeo link        'https://vimeo.com/123456789'
 *   A file you drop   'videos/compliance-walkthrough.mp4'   (put the file in site/public/videos/)
 *   Any direct link   'https://cdn.example.com/film.mp4'    (R2, S3, Bunny, Dropbox direct link...)
 *
 * Leave `src` as '' and the card says "Footage coming soon" instead of showing
 * a broken player.
 *
 * `poster` is optional — a still image shown before play. YouTube and Vimeo
 * thumbnails are fetched automatically, so it is mostly for .mp4 files:
 * drop a JPG in site/public/posters/ and reference it as 'posters/name.jpg'.
 *
 * `client` and `duration` are optional labels on the card.
 *
 * `service` must match one of the slugs in SERVICES below. `category` is an
 * optional sub-heading within a service (Animation splits into 2D and 3D).
 */
export const videos = [
    // Articulate
    { service: 'articulate', title: 'Introduction to Semiotic Analysis', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/articulate-introduction-to-semiotic-analysis.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/articulate-introduction-to-semiotic-analysis.jpg' },
    { service: 'articulate', title: 'Sales and Operations Planning', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/articulate-sales-and-operations-planning.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/articulate-sales-and-operations-planning.jpg' },
    { service: 'articulate', title: 'DSU Unit 8.1', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/articulate-dsu-unit-81.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/articulate-dsu-unit-81.jpg' },
    { service: 'articulate', title: 'DSU Unit 8.2', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/articulate-dsu-unit-82.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/articulate-dsu-unit-82.jpg' },
    { service: 'articulate', title: 'Problem Solving using C: Unit 12.1', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/articulate-problem-solving-using-c-unit-121.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/articulate-problem-solving-using-c-unit-121.jpg' },

    // Infographics
    { service: 'infographics', title: 'Advance Settings', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/infographics-advance-settings.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/infographics-advance-settings.jpg' },
    { service: 'infographics', title: 'Vision: The Golden Circle', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/infographics-vision-the-golden-circle.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/infographics-vision-the-golden-circle.jpg' },
    { service: 'infographics', title: 'BIT WR 1.1.1.1', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/infographics-bit-wr-1111.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/infographics-bit-wr-1111.jpg' },
    { service: 'infographics', title: 'Creating an Effective IMC Plan', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/infographics-creating-an-effective-imc-plan.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/infographics-creating-an-effective-imc-plan.jpg' },

    // Animation
    { service: 'animation', category: '2D Animation', title: 'Code of Conduct', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-code-of-conduct.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-code-of-conduct.jpg' },
    { service: 'animation', category: '2D Animation', title: 'IEEE', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-ieee.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-ieee.jpg' },
    { service: 'animation', category: '2D Animation', title: 'NBM 2024 Presentation Deck', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-nbm-2024-presentation-deck.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-nbm-2024-presentation-deck.jpg' },
    { service: 'animation', category: '2D Animation', title: 'QR Charging', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-qr-charging.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-qr-charging.jpg' },
    { service: 'animation', category: '3D Animation', title: 'Blood Vessel', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-blood-vessel.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-blood-vessel.jpg' },
    { service: 'animation', category: '3D Animation', title: 'RedFx', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/animation-redfx.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/animation-redfx.jpg' },

    // AI Videos
    { service: 'ai-videos', title: 'AI Based Learning Sample', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/ai-videos-ai-based-learning-sample.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/ai-videos-ai-based-learning-sample.jpg' },
    { service: 'ai-videos', title: 'Emversity EWS', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/ai-videos-emversity-ews.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/ai-videos-emversity-ews.jpg' },

    // Smart Board
    { service: 'smart-board', title: 'Pricing Fundamentals, Methods & Strategies', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/smart-board-pricing-fundamentals-methods-strategies.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/smart-board-pricing-fundamentals-methods-strategies.jpg' },

    // Motion Graphics
    { service: 'motion-graphics', title: 'Adulting', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/motion-graphics-adulting.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/motion-graphics-adulting.jpg' },
    { service: 'motion-graphics', title: 'Digital Design', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/motion-graphics-digital-design.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/motion-graphics-digital-design.jpg' },
    { service: 'motion-graphics', title: 'Emerging Technologies (Hindi Dubbing)', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/motion-graphics-emerging-technologies-hindi-dubbing.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/motion-graphics-emerging-technologies-hindi-dubbing.jpg' },
    { service: 'motion-graphics', title: 'IEEE Cybersecurity Intro', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/motion-graphics-ieee-cybersecurity-intro.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/motion-graphics-ieee-cybersecurity-intro.jpg' },
    { service: 'motion-graphics', title: 'Sample Video 1', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/motion-graphics-sample-video-1.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/motion-graphics-sample-video-1.jpg' },

    // Swayam
    { service: 'swayam', title: 'Cyber Security Course Intro', client: '', duration: '', src: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/swayam-cyber-security-course-intro.mp4', poster: 'https://pub-fbba415a5af44e0dbcd4c54f49b8d921.r2.dev/films/posters/swayam-cyber-security-course-intro.jpg' },
]

/**
 * The four numbers in the "Who we are" panel beside the hero. Each is built
 * like a sample card, so it carries its own two colour stops too.
 * `value` is what counts up; `suffix` is printed after it (e.g. '+', '%').
 */
export const stats = [
    { value: '2018', suffix: '', label: 'Founded in Bengaluru', accent: ['#15D9A1', '#00615C'] },
    { value: '50', suffix: '+', label: 'Clients', accent: ['#F2AA84', '#A31009'] },
    { value: '100', suffix: '%', label: 'In-House Multidisciplinary Team', accent: ['#15D9A1', '#00615C'] },
]

/**
 * The seven lines of work. Order here is the order on the page. Each carries
 * its own two colour stops, used for the section accent and the card washes.
 */
export const services = [
    {
        slug: 'articulate',
        name: 'Articulate',
        tagline: 'Storyline and Rise builds that people finish',
        description: 'Interactive courses authored in Articulate Storyline and Rise, from scripting and screen design through to SCORM packaging and LMS handover.',
        accent: ['#15D9A1', '#00615C'],
    },
    {
        slug: 'infographics',
        name: 'Infographics',
        tagline: 'Complex ideas, made legible at a glance',
        description: 'Static and animated infographics that turn data, processes and policy into visuals people actually read, designed for slides, print, social and course pages alike.',
        accent: ['#FFD166', '#B4530A'],
    },
    {
        slug: 'animation',
        name: 'Animation',
        tagline: 'Drawn by hand, modelled in depth',
        description: 'Character and vector animation for explainers, campaign films and course openers, alongside product and process animation in three dimensions for the ideas that only make sense when you can turn them around and look inside.',
        accent: ['#69FFF7', '#008680'],
    },
    {
        slug: 'ai-videos',
        name: 'AI Videos',
        tagline: 'Synthetic presenters, produced properly',
        description: 'AI-assisted presenters and voice for content that needs to ship at volume, scripted and directed with the same care as a live shoot.',
        accent: ['#F2AA84', '#A31009'],
    },
    {
        slug: 'smart-board',
        name: 'Smart Board',
        tagline: 'The film, before the film',
        description: 'Boards and animatics that settle pacing, framing and intent before a single frame is produced, so production time is spent building rather than deciding.',
        accent: ['#F8847E', '#800D07'],
    },
    {
        slug: 'motion-graphics',
        name: 'Motion Graphics',
        tagline: 'Type, data and identity in motion',
        description: 'Titles, lower thirds, data visualisation and brand systems in motion, for films that need to look like they belong to someone.',
        accent: ['#FEF1DE', '#A31009'],
    },
    {
        slug: 'swayam',
        name: 'Swayam',
        tagline: 'MOOC lectures, produced to broadcast standard',
        description: 'End-to-end production for SWAYAM and MOOC courses: studio-recorded lectures, multi-camera sessions and screen capture, edited, captioned and packaged to platform specification.',
        accent: ['#C1FAFB', '#00615C'],
    },
]

/**
 * Company details for the footer.
 */
export const company = {
    name: 'Maieutic Edutech Pvt Ltd',
    site: 'https://maieuticedutech.com',
    blurb: 'Empowering education through innovation and technology — from content to campus, online.',
    email: 'careers@maieuticedutech.com',
    phone: '+91 96637 27955',
    phoneHref: 'tel:+919663727955',
    address: ['248/1, 3rd floor, Above 154 Breakfast Restaurant', 'Kenchena Halli Road, 2nd Main Rd,', 'Halagevadera Halli, Rajarajeshwari Nagar,', 'Bengaluru, Karnataka 560098'],
    socials: [
        ['LinkedIn', 'https://www.linkedin.com/company/maieuticedutech', 'M4.98 3.5C4.98 4.88 3.87 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5ZM.22 8.22h4.56V23H.22V8.22Zm7.34 0h4.37v2.02h.06c.61-1.15 2.1-2.37 4.32-2.37 4.62 0 5.47 3.04 5.47 6.99V23h-4.55v-7.2c0-1.72-.03-3.93-2.39-3.93-2.4 0-2.76 1.87-2.76 3.8V23H7.56V8.22Z'],
        ['YouTube', 'https://www.youtube.com/@maieuticedutech', 'M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2C0 8.1 0 12 0 12s0 3.9.5 5.8a3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z'],
        ['Instagram', 'https://www.instagram.com/maieutic2018', 'M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2-.1-1.3-.1-1.6-.1-4.8s0-3.6.1-4.8c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4 1.2-.1 1.6-.1 4.8-.1ZM12 0C8.7 0 8.3 0 7.1.1 5.8.1 4.9.3 4.1.6c-.8.3-1.5.7-2.2 1.4C1.3 2.7.9 3.4.6 4.2.3 5 .1 5.8.1 7.1 0 8.3 0 8.7 0 12s0 3.7.1 4.9c.1 1.3.3 2.1.6 2.9.3.8.7 1.5 1.4 2.2.7.7 1.4 1.1 2.2 1.4.8.3 1.6.5 2.9.6 1.2.1 1.6.1 4.9.1s3.7 0 4.9-.1c1.3-.1 2.1-.3 2.9-.6.8-.3 1.5-.7 2.2-1.4.7-.7 1.1-1.4 1.4-2.2.3-.8.5-1.6.6-2.9.1-1.2.1-1.6.1-4.9s0-3.7-.1-4.9c-.1-1.3-.3-2.1-.6-2.9-.3-.8-.7-1.5-1.4-2.2C21.3 1.3 20.6.9 19.8.6 19 .3 18.2.1 16.9.1 15.7 0 15.3 0 12 0Zm0 5.8a6.2 6.2 0 1 0 0 12.4 6.2 6.2 0 0 0 0-12.4ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.4-11.8a1.4 1.4 0 1 0 0 2.9 1.4 1.4 0 0 0 0-2.9Z'],
        ['Facebook', 'https://www.facebook.com/maieuticedutech', 'M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.09 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.02 1.79-4.69 4.53-4.69 1.31 0 2.68.24 2.68.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.88v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.09 24 18.1 24 12.07Z'],
    ],
    columns: {
        'Solutions': [
            ['All Solutions', '/solutions'],
            ['Content Design & Development', '/solutions/content-design-development'],
            ['Marketing, Digital Products & Network', '/solutions/marketing-digital-products'],
            ['Academic Delivery', '/solutions/academic-delivery-student-success'],
            ['LMS Deployment & Management', '/solutions/lms-deployment-management'],
            ['Interactive Models & Articulate', '/solutions/interactive-models-articulate'],
            ['Video Based Learning', '/solutions/video-based-learning'],
            ['2D / 3D / Motion Graphics', '/solutions/2d-3d-motion-graphics'],
        ],
        'Company': [
            ['Who We Are', '/about-us'],
            ['Our Vision', '/about-us'],
            ['Our Mission', '/about-us'],
            ['Our Impact', '/about-us'],
            ['Our Clients', '/clients'],
            ['Careers', '/careers'],
            ['Gallery', '/gallery'],
        ],
        'Resources': [
            ['Blogs & Insights', '/resources/blogs-insights'],
            ['Case Studies', '/resources/case-studies'],
            ['FAQs', '/faqs'],
        ],
        'Contact Us': [
            ['Send Us a Message', '/contact'],
            ['Email Us', 'mailto:careers@maieuticedutech.com'],
            ['Call Us', 'tel:+919663727955'],
            ['Our Location', '/contact'],
        ],
    },
}
