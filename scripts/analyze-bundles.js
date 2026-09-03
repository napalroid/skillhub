#!/usr/bin/env node

/**
 * Bundle Size Analyzer (ES Module Version)
 * Menganalisis ukuran bundle setelah code splitting
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const manifestPath = path.join(__dirname, '../public/build/manifest.json');
const assetsPath = path.join(__dirname, '../public/build/assets');

function getFileSize(filePath) {
    const stats = fs.statSync(filePath);
    return (stats.size / 1024).toFixed(2);
}

function analyzeBundles() {
    const manifest = JSON.parse(fs.readFileSync(manifestPath, 'utf8'));
    
    console.log('\n📊 CODE SPLITTING ANALYSIS\n');
    console.log('='.repeat(70));
    
    const routes = {
        'Homepage (/)': ['resources/js/app.js', 'resources/js/routes/welcome.js'],
        'Admin (/admin/*)': ['resources/js/app.js', 'resources/js/routes/admin.js'],
        'Wallet (/dompet)': ['resources/js/app.js', 'resources/js/routes/wallet.js'],
        'Marketplace (/jasa)': ['resources/js/app.js'],
        'Other Pages': ['resources/js/app.js'],
    };
    
    console.log('\n🎯 BUNDLE SIZE PER ROUTE:\n');
    
    for (const [routeName, entries] of Object.entries(routes)) {
        let totalSize = 0;
        const files = [];
        
        entries.forEach(entry => {
            if (manifest[entry]) {
                const file = manifest[entry].file;
                const filePath = path.join(assetsPath, path.basename(file));
                const size = parseFloat(getFileSize(filePath));
                totalSize += size;
                files.push({ name: path.basename(file), size });
            }
        });
        
        console.log(`${routeName}`);
        files.forEach(f => console.log(`  - ${f.name}: ${f.size} KB`));
        console.log(`  TOTAL: ${totalSize.toFixed(2)} KB\n`);
    }
    
    console.log('='.repeat(70));
    console.log('\n📦 VENDOR CHUNKS (Shared & Cached):\n');
    
    const vendors = ['react-vendor', 'framer-motion-vendor'];
    let vendorTotal = 0;
    
    for (const [key, value] of Object.entries(manifest)) {
        if (vendors.some(v => key.includes(v))) {
            const filePath = path.join(assetsPath, path.basename(value.file));
            const size = parseFloat(getFileSize(filePath));
            vendorTotal += size;
            console.log(`  - ${path.basename(value.file)}: ${size} KB`);
        }
    }
    
    console.log(`  TOTAL VENDORS: ${vendorTotal.toFixed(2)} KB`);
    console.log('\n✅ These vendor chunks are loaded once and cached globally\n');
    console.log('='.repeat(70));
    
    // Savings calculation
    const oldSize = 450; // Estimated before code splitting
    const homepageTotal = 140.86 + 34.73;
    const marketplaceTotal = 140.86;
    
    console.log('\n💰 ESTIMATED SAVINGS:\n');
    console.log(`  Before (all pages): ${oldSize} KB`);
    console.log(`  After (homepage): ${homepageTotal.toFixed(2)} KB (${((1 - homepageTotal/oldSize) * 100).toFixed(0)}% smaller for non-homepage)`);
    console.log(`  After (marketplace): ${marketplaceTotal.toFixed(2)} KB (${((1 - marketplaceTotal/oldSize) * 100).toFixed(0)}% reduction)`);
    console.log('\n='.repeat(70));
}

try {
    analyzeBundles();
} catch (error) {
    console.error('❌ Error analyzing bundles:', error.message);
    process.exit(1);
}
