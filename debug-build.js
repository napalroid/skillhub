import { build } from 'vite';

try {
    console.log('Starting build...');
    await build({
        configFile: './vite.config.js',
        logLevel: 'error'
    });
    console.log('Build completed successfully!');
} catch (error) {
    console.error('Build failed with error:');
    console.error(error);
    console.error('Stack trace:', error.stack);
    console.error('Message:', error.message);
    if (error.errors) {
        console.error('Errors:', error.errors);
    }
    process.exit(1);
}
