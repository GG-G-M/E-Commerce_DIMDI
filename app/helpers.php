<?php

// Custom helper functions for the application

if (!function_exists('format_currency')) {
    /**
     * Format currency with proper peso symbol for PDF generation
     * 
     * @param float $amount
     * @param bool $forPdf Whether this is for PDF generation
     * @return string
     */
    function format_currency($amount, $forPdf = false)
    {
        // Always use direct peso symbol for both web and PDF
        return '₱' . number_format($amount, 2);
    }
}

if (!function_exists('pdf_currency')) {
    /**
     * Format currency specifically for PDF generation
     * 
     * @param float $amount
     * @return string
     */
    function pdf_currency($amount)
    {
        // Use PHP prefix for better compatibility in PDF generation
        return 'PHP ' . number_format($amount, 2);
    }
}