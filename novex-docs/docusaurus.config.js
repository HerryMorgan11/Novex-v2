// @ts-check
// `@type` JSDoc annotations allow editor autocompletion and type checking
// (when paired with `@ts-check`).
// There are various equivalent ways to declare your Docusaurus config.
// See: https://docusaurus.io/docs/api/docusaurus-config

import { themes as prismThemes } from 'prism-react-renderer';

// This runs in Node.js - Don't use client-side code here (browser APIs, JSX...)

/** @type {import('@docusaurus/types').Config} */
const config = {
    title: 'Novex v2 Documentation',
    tagline: 'Documentation for Novex v2 ERP',
    favicon: 'img/favicon.ico',

    // Future flags, see https://docusaurus.io/docs/api/docusaurus-config#future
    future: {
        v4: true, // Improve compatibility with the upcoming Docusaurus v4
    },

    // Set the production url of your site here
    url: 'https://your-docusaurus-site.example.com',
    // Set the /<baseUrl>/ pathname under which your site is served
    // For GitHub pages deployment, it is often '/<projectName>/'
    baseUrl: '/',

    // GitHub pages deployment config.
    // If you aren't using GitHub pages, you don't need these.
    organizationName: 'HerryMorgan11', // Usually your GitHub org/user name.
    projectName: 'Novex-v2', // Usually your repo name.

    onBrokenLinks: 'throw',

    // Even if you don't use internationalization, you can use this field to set
    // useful metadata like html lang. For example, if your site is Chinese, you
    // may want to replace "en" with "zh-Hans".
    i18n: {
        defaultLocale: 'en',
        locales: ['en'],
    },

    presets: [
        [
            'classic',
            /** @type {import('@docusaurus/preset-classic').Options} */
            ({
                docs: {
                    sidebarPath: './sidebars.js',
                    // Please change this to your repo.
                    // Remove this to remove the "edit this page" links.
                    // editUrl: 'https://github.com/facebook/docusaurus/tree/main/packages/create-docusaurus/templates/shared/',
                },
                blog: {
                    showReadingTime: true,
                    feedOptions: {
                        type: ['rss', 'atom'],
                        xslt: true,
                    },
                    // Please change this to your repo.
                    // Remove this to remove the "edit this page" links.
                    // editUrl: 'https://github.com/facebook/docusaurus/tree/main/packages/create-docusaurus/templates/shared/',
                    // Useful options to enforce blogging best practices
                    onInlineTags: 'warn',
                    onInlineAuthors: 'warn',
                    onUntruncatedBlogPosts: 'warn',
                },
                theme: {
                    customCss: './src/css/custom.css',
                },
            }),
        ],
    ],

    themeConfig:
        /** @type {import('@docusaurus/preset-classic').ThemeConfig} */
        ({
            // Replace with your project's social card
            image: 'img/docusaurus-social-card.jpg',
            colorMode: {
                respectPrefersColorScheme: true,
            },
            navbar: {
                title: 'Novex v2',
                logo: {
                    alt: 'Novex Logo',
                    src: 'img/logo-novex.png',
                },
                items: [
                    {
                        type: 'docSidebar',
                        sidebarId: 'docs',
                        position: 'left',
                        label: 'Documentación',
                    },
                    {
                        to: '/docs/architecture/overview',
                        label: 'Memoria Técnica',
                        position: 'left',
                    },
                    {
                        href: 'https://github.com/HerryMorgan11/Novex-v2',
                        label: 'GitHub',
                        position: 'right',
                    },
                ],
            },
            footer: {
                style: 'dark',
                links: [
                    {
                        title: 'Docs',
                        items: [
                            {
                                label: 'Get Started',
                                to: '/docs/getting-started/quick-start',
                            },
                            {
                                label: 'Architecture',
                                to: '/docs/architecture/overview',
                            },
                            {
                                label: 'Project Roadmap',
                                to: '/docs/project/roadmap',
                            },
                        ],
                    },
                    {
                        title: 'Project',
                        items: [
                            {
                                label: 'GitHub Repository',
                                href: 'https://github.com/HerryMorgan11/Novex-v2',
                            },
                            {
                                label: 'Issues',
                                href: 'https://github.com/HerryMorgan11/Novex-v2/issues',
                            },
                        ],
                    },
                    {
                        title: 'Modules',
                        items: [
                            {
                                label: 'Inventory',
                                to: '/docs/project/issues/fase-5/issue-5.1-estructura-modulo',
                            },
                            {
                                label: 'Landing Page',
                                to: '/docs/features/landing',
                            },
                        ],
                    },
                ],
                copyright: `Copyright © ${new Date().getFullYear()} Novex v2 ERP. Built with Docusaurus.`,
            },
            prism: {
                theme: prismThemes.github,
                darkTheme: prismThemes.dracula,
            },
        }),
};

export default config;
