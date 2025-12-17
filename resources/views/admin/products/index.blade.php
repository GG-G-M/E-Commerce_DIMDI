@extends('layouts.admin')

@section('content')
<style>
    /* === Consistent Green Theme === */
    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }

    .card-header-custom {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        font-weight: 600;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* Summary Cards - Consistent */
    .summary-card {
        background: linear-gradient(135deg, #E8F5E6, #F8FDF8);
        border: none;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .summary-number {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2C8F0C;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .summary-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
    }

    /* CSV Import Button - Matching Add Product Button */
    .btn-import-csv {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-import-csv:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-import-csv:active {
        transform: translateY(0);
    }

    /* Add Product Button */
    .btn-add-product {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(44, 143, 12, 0.2);
        height: 46px;
    }
    
    .btn-add-product:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(44, 143, 12, 0.3);
        color: white;
    }

    .btn-add-product:active {
        transform: translateY(0);
    }

    /* Table Styling - Compact */
    .table {
        margin-bottom: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }
    
    /* Center align all table headers and cells */
    .table th,
    .table td {
        text-align: center;
        vertical-align: middle;
    }
    
    /* Product column - center aligned */
    .table td.name-col {
        text-align: center;
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
    }
    
    .table th.name-col {
        text-align: center;
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
    }
    
    /* Product column content centering */
    .table td.name-col .text-center {
        text-align: center;
        width: 100%;
    }
    
    .table td.name-col .product-name,
    .table td.name-col .product-sku {
        text-align: center;
        display: block;
        margin: 0 auto;
    }
    
    /* Product name and SKU specific styling */
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        line-height: 1.2;
        margin-bottom: 2px;
        text-align: center;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
        margin-top: 0;
        text-align: center;
    }
    
    /* Ensure image column is properly centered */
    .table td.image-col,
    .table th.image-col {
        text-align: center;
        padding: 0.75rem 0.25rem;
    }
    
    .table td.image-col .product-img {
        margin: 0 auto;
        display: block;
    }

    .table th {
        background-color: #E8F5E6;
        color: #2C8F0C;
        font-weight: 600;
        border-bottom: 2px solid #2C8F0C;
        padding: 0.75rem 0.5rem;
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
    }

    .table td {
        padding: 0.75rem 0.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }

    .table tbody tr:hover {
        background-color: #F8FDF8;
        transition: background-color 0.2s ease;
    }

    /* Alternating row colors */
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:nth-child(even):hover {
        background-color: #F8FDF8;
    }

    /* Status Styling - Text with pulsing dots */
    .status-text {
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-text-active {
        color: #2C8F0C;
    }
    
    .status-text-active::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #2C8F0C;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-text-inactive {
        color: #6c757d;
    }
    
    .status-text-inactive::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    .status-text-archived {
        color: #6c757d;
    }
    
    .status-text-archived::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #6c757d;
        border-radius: 50%;
        opacity: 0.6;
    }
    
    .status-featured {
        color: #2C8F0C;
    }
    
    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.6;
        }

        100% {
            opacity: 1;
        }
    }

    /* Stock Level Styling */
    .stock-high {
        color: #2C8F0C;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-low {
        color: #FBC02D;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .stock-out {
        color: #C62828;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Filter Section - Consistent */
    .search-loading {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        display: none;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        border: 2px solid;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Table styling for no scroll bars */
    .table {
        width: 100%;
        max-width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    /* Prevent any scroll bars in the table card */
    .card-custom .card-body {
        overflow-x: hidden;
        overflow-y: hidden;
    }

    .card-custom {
        overflow: hidden;
    }

    /* Responsive table - always fixed layout for better fit */
    .table {
        table-layout: fixed;
    }

    /* Column widths */
    .id-col { width: 60px; min-width: 60px; text-align: center; }
    .image-col { width: 70px; min-width: 70px; text-align: center; }
    .name-col { width: 180px; min-width: 180px; text-align: center; }
    .brand-col { width: 100px; min-width: 100px; text-align: center; }
    .category-col { width: 100px; min-width: 100px; text-align: center; }
    .variants-col { width: 80px; min-width: 80px; text-align: center; }
    .price-col { width: 90px; min-width: 90px; text-align: center; }
    .stock-col { width: 80px; min-width: 80px; text-align: center; }
    .status-col { width: 80px; min-width: 80px; text-align: center; }
    .action-col { width: 120px; min-width: 120px; text-align: center; }

    /* Product Image */
    .product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    

    


    /* Price Styling */
    .price-current {
        font-weight: 700;
        font-size: 0.9rem;
        color: #2C8F0C;
    }
    
    .price-original {
        color: #6c757d;
        font-size: 0.75rem;
        text-decoration: line-through;
    }
    
    .discount-badge {
        font-size: 0.7rem;
        background-color: #FFEBEE;
        color: #C62828;
        padding: 0.15rem 0.35rem;
        border-radius: 4px;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #C8E6C9;
        margin-bottom: 1rem;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Action Buttons - Consistent with other pages */
    .action-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        padding: 2px 0;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border: 1.5px solid;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn-view {
        background-color: white;
        border-color: #4CAF50;
        color: #4CAF50;
    }
    
    .btn-view:hover {
        background-color: #4CAF50;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-edit {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-edit:hover {
        background-color: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-archive {
        background-color: white;
        border-color: #FBC02D;
        color: #FBC02D;
    }
    
    .btn-archive:hover {
        background-color: #FBC02D;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-unarchive {
        background-color: white;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .btn-unarchive:hover {
        background-color: #2C8F0C;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Ensure forms don't interfere with button layout */
    .action-buttons form {
        display: inline;
        margin: 0;
        padding: 0;
    }
    
    .action-buttons form button {
        margin: 0;
        padding: 0;
    }
    
    /* Prevent action column from wrapping */
    .action-col {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Ensure table cells don't expand */
    .table td.action-col {
        overflow: hidden;
    }
    
    /* Fix table layout for better action button display */
    .table {
        table-layout: fixed;
        word-wrap: break-word;
    }

    /* Modern View Modal Styling */
    .view-modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(44, 143, 12, 0.15);
    }

    /* === Customer/Product Avatar Styling === */
    .view-avatar-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.2);
        overflow: hidden;
    }

    .view-customer-name {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .view-customer-id {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* View Modal Card Styling */
    .view-info-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        height: 100%;
    }

    .view-info-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border-color: #2C8F0C;
    }

    .view-info-card .form-label {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1rem;
        display: block;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #E8F5E6;
    }

    .view-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .view-detail-item:last-child {
        border-bottom: none;
    }

    .view-detail-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        flex: 0 0 100px;
    }

    .view-detail-value {
        color: #212529;
        font-size: 0.9rem;
        text-align: right;
        flex: 1;
        word-break: break-word;
        margin-left: 0.5rem;
    }

    /* === Premium Modern Header Section === */
    .view-header-section {
        background: linear-gradient(135deg, #f8fdf8 0%, #e8f5e6 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 2px solid #c8e6c9;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(44, 143, 12, 0.1);
    }

    .view-header-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2C8F0C, #4CAF50, #66BB6A, #81C784);
        border-radius: 20px 20px 0 0;
    }

    /* === Centered Card Layout === */
    .view-product-showcase {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 3rem;
        align-items: center;
        text-align: center;
    }

    .view-product-image-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .view-product-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 20px;
        border: 4px solid #2C8F0C;
        background-color: #f8f9fa;
        box-shadow: 0 8px 32px rgba(44, 143, 12, 0.2);
        display: block;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .view-product-image:hover {
        transform: translateY(-8px) scale(1.05);
        box-shadow: 0 20px 60px rgba(44, 143, 12, 0.3);
    }

    .view-product-info {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }

    .view-product-name {
        color: #2C8F0C;
        font-weight: 800;
        font-size: 1.5rem;
        margin: 0;
        line-height: 1.2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .view-product-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .view-product-sku {
        color: #495057;
        font-size: 1rem;
        font-family: 'SF Mono', Monaco, monospace;
        background: linear-gradient(135deg, #e9ecef, #f8f9fa);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 2px solid #dee2e6;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .view-product-sku:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        border-color: #2C8F0C;
    }

    .view-product-id {
        color: #6c757d;
        font-size: 0.95rem;
        font-weight: 600;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 2px solid #dee2e6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Content Sections */
    .view-content-sections {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .view-section {
        background: white;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
    }

    .view-section:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border-color: #2C8F0C;
    }

    .view-section-header {
        background: linear-gradient(135deg, #E8F5E6, #f8fdf8);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #c8e6c9;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .view-section-header i {
        color: #2C8F0C;
        font-size: 1.1rem;
    }

    .view-section-header h4 {
        color: #2C8F0C;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .view-section-content {
        padding: 1.5rem;
    }

    .view-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .view-info-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .view-info-group.full-width {
        grid-column: 1 / -1;
    }

    .view-info-label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-info-value {
        color: #212529;
        font-size: 1rem;
        font-weight: 500;
        word-break: break-word;
        line-height: 1.4;
    }

    .view-product-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        background-color: #f8f9fa;
        margin: 0 auto 1rem;
        display: block;
    }

    /* === Responsive Design === */
    @media (max-width: 768px) {
        .view-product-showcase {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            text-align: center;
        }
        
        .view-product-image {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
        
        .view-product-name {
            font-size: 1.5rem;
        }
        
        .view-product-meta {
            justify-content: center;
        }
        
        .view-price-display {
            padding: 1.5rem;
        }
        
        .view-current-price {
            font-size: 1.8rem;
        }
    }

    /* Compact grid layout for cards */
    .view-cards-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .view-cards-grid .view-info-card:nth-child(1),
    .view-cards-grid .view-info-card:nth-child(2),
    .view-cards-grid .view-info-card:nth-child(3),
    .view-cards-grid .view-info-card:nth-child(4) {
        grid-column: span 1;
    }

    /* === Enhanced Status Badges === */
    .view-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        border: 3px solid;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .view-status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .view-status-badge:hover::before {
        left: 100%;
    }

    .view-status-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .view-status-active {
        background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
        color: #166534;
        border-color: #22C55E;
    }

    .view-status-inactive {
        background: linear-gradient(135deg, #FEE2E2, #FECACA);
        color: #991B1B;
        border-color: #EF4444;
    }

    .view-status-archived {
        background: linear-gradient(135deg, #F3F4F6, #E5E7EB);
        color: #4B5563;
        border-color: #9CA3AF;
    }

    /* === Premium Price Display Card === */
    .view-price-display {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        border-radius: 20px;
        padding: 2.5rem;
        border: 3px solid #2C8F0C;
        text-align: center;
        box-shadow: 0 10px 40px rgba(44, 143, 12, 0.2);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .view-price-display::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 4s infinite;
    }

    @keyframes shimmer {
        0%, 100% { transform: rotate(0deg); opacity: 0.5; }
        50% { transform: rotate(180deg); opacity: 1; }
    }

    .view-price-display:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 60px rgba(44, 143, 12, 0.3);
    }

    .view-current-price {
        font-size: 2.5rem;
        font-weight: 900;
        color: #2C8F0C;
        line-height: 1;
        margin: 0;
        text-shadow: 0 2px 8px rgba(0,0,0,0.1);
        position: relative;
        z-index: 1;
    }

    .view-original-price {
        font-size: 1.3rem;
        color: #6c757d;
        text-decoration: line-through;
        margin-top: 0.75rem;
        position: relative;
        z-index: 1;
    }

    .view-discount-badge {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 700;
        display: inline-block;
        margin-top: 1rem;
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        position: relative;
        z-index: 1;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        animation: pulse-glow 2s infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3); }
        50% { box-shadow: 0 6px 30px rgba(220, 53, 69, 0.5); }
    }

    .view-variants-list {
        max-height: 200px;
        overflow-y: auto;
    }

    .view-variant-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f8f9fa;
        transition: background-color 0.2s ease;
    }

    .view-variant-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .view-variant-image {
        flex-shrink: 0;
    }

    .variant-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .view-variant-details {
        flex: 1;
        min-width: 0;
    }

    .view-variant-item:hover {
        background-color: #f8f9fa;
        margin: 0 -1.25rem;
        padding-left: 1.25rem;
        padding-right: 1.25rem;
        border-radius: 8px;
    }

    .view-variant-item:last-child {
        border-bottom: none;
    }

    .view-variant-name {
        font-weight: 600;
        color: #2C8F0C;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .view-variant-price {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .view-variant-discount {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    .view-variant-stock {
        color: #2C8F0C;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        font-weight: 600;
    }

    .view-no-variants {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fdf8, #f0f8f0);
        border-radius: 12px;
        border: 1px dashed #c8e6c9;
    }

    .no-variants-icon {
        font-size: 3rem;
        color: #c8e6c9;
        margin-bottom: 1rem;
    }

    .no-variants-text h5 {
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .no-variants-text p {
        color: #8c9aa5;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .view-product-showcase {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }

        .view-product-image {
            width: 100px;
            height: 100px;
        }

        .view-product-meta {
            justify-content: center;
        }

        .view-info-grid {
            grid-template-columns: 1fr;
        }

        .view-info-group.full-width {
            grid-column: 1 / -1;
        }

        .view-section-content {
            padding: 1rem;
        }

        .view-section-header {
            padding: 0.75rem 1rem;
        }

        .view-section-header h4 {
            font-size: 1rem;
        }
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .action-col { 
            width: 100px; 
            min-width: 100px; 
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }
        
        .action-buttons {
            gap: 2px;
        }
        
        .product-img {
            width: 50px;
            height: 50px;
        }
        
        .summary-number {
            font-size: 1.5rem;
        }
        
        .summary-label {
            font-size: 0.8rem;
        }
        
        .status-text {
            font-size: 0.8rem;
        }
    }
    
    /* Extra small screens - stack action buttons vertically */
    @media (max-width: 576px) {
        .action-col { 
            width: 90px; 
            min-width: 90px; 
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 2px;
            align-items: center;
        }
        
        .action-btn {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }
    }

    /* === Custom Confirmation Dialog === */
    .custom-confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        display: flex;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(2px);
    }

    .custom-confirm-dialog {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(44, 143, 12, 0.3);
        max-width: 400px;
        width: 90%;
        transform: scale(0.9);
        animation: confirmModalIn 0.3s ease-out forwards;
        overflow: hidden;
        border: 2px solid #E8F5E6;
    }

    @keyframes confirmModalIn {
        to {
            transform: scale(1);
        }
    }

    .custom-confirm-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        padding: 1.5rem;
        text-align: center;
        position: relative;
    }

    .custom-confirm-header::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    }

    .custom-confirm-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .custom-confirm-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .custom-confirm-body {
        padding: 2rem;
        text-align: center;
        background: linear-gradient(135deg, #f8fdf8 0%, #f0f8f0 100%);
    }

    .custom-confirm-message {
        font-size: 1rem;
        color: #495057;
        line-height: 1.6;
        margin: 0;
        font-weight: 500;
    }

    .custom-confirm-buttons {
        display: flex;
        gap: 0.75rem;
        padding: 1.5rem 2rem 2rem;
        background: white;
        justify-content: center;
    }

    .custom-confirm-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        min-width: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .custom-confirm-btn-cancel {
        background: #f8f9fa;
        color: #6c757d;
        border: 2px solid #dee2e6;
    }

    .custom-confirm-btn-cancel:hover {
        background: #e9ecef;
        border-color: #adb5bd;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        color: #495057;
    }

    .custom-confirm-btn-confirm {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border: 2px solid #2C8F0C;
        box-shadow: 0 4px 12px rgba(44, 143, 12, 0.3);
    }

    .custom-confirm-btn-confirm:hover {
        background: linear-gradient(135deg, #1E6A08, #2C8F0C);
        border-color: #1E6A08;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(44, 143, 12, 0.4);
    }

    .custom-confirm-btn-confirm.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        border-color: #dc3545;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .custom-confirm-btn-confirm.danger:hover {
        background: linear-gradient(135deg, #c82333, #bd2130);
        border-color: #bd2130;
        box-shadow: 0 6px 16px rgba(220, 53, 69, 0.4);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .custom-confirm-dialog {
            width: 95%;
            margin: 1rem;
        }
        
        .custom-confirm-body {
            padding: 1.5rem;
        }
        
        .custom-confirm-buttons {
            flex-direction: column;
            gap: 0.5rem;
            padding: 1rem 1.5rem 1.5rem;
        }
        
        .custom-confirm-btn {
            width: 100%;
        }
    }

    /* Product Image */
    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }

    /* Product Info */
    .product-name {
        font-weight: 600;
        color: #333;
        font-size: 0.85rem;
        line-height: 1.2;
    }
    
    .product-sku {
        color: #6c757d;
        font-size: 0.75rem;
    }

    /* Price Styling */
    .price-current {
        font-weight: 700;
        color: #2C8F0C;
        font-size: 0.9rem;
    }
    
    .price-original {
        font-size: 0.75rem;
        color: #6c757d;
        text-decoration: line-through;
    }
    
    .discount-badge {
        font-size: 0.7rem;
        font-weight: 600;
        color: #C62828;
        background-color: #FFEBEE;
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        margin-left: 0.25rem;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 1.5rem;
    }

    /* Header Stats */
    .header-stats {
        font-size: 0.9rem;
        font-weight: 600;
        opacity: 0.9;
    }

    /* Category Text */
    .category-text {
        color: #495057;
        font-size: 0.85rem;
    }
    
    .category-warning {
        color: #FBC02D;
        font-size: 0.7rem;
    }

    /* Brand Text */
    .brand-text {
        color: #495057;
        font-size: 0.85rem;
    }

    /* Variants Info */
    .variants-info {
        color: #495057;
        font-size: 0.85rem;
    }

    /* Pagination styling - Consistent */
    .pagination .page-item .page-link {
        color: #2C8F0C;
        border: 1px solid #dee2e6;
        margin: 0 1px;
        border-radius: 4px;
        transition: all 0.3s ease;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        border-color: #2C8F0C;
        color: white;
    }
    
    .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #E8FDF8;
        border-color: #2C8F0C;
        color: #2C8F0C;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
    }

    /* Header button group */
    .header-buttons {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    
    .header-buttons .btn {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Modal Styling */
    .modal-header {
        background: linear-gradient(135deg, #2C8F0C, #4CAF50);
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1rem;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    /* Template Download Section */
    .template-download {
        background: #E8F5E6;
        border: 1px dashed #2C8F0C;
        padding: 1rem;
        text-align: center;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    /* Instructions */
    .csv-instructions {
        background: #f8f9fa;
        border-left: 4px solid #2C8F0C;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    /* Make table more compact on mobile */
    @media (max-width: 768px) {
        .header-buttons {
            flex-direction: column;
            gap: 5px;
        }
        
        .table th,
        .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
        }
        
        .action-btn {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .btn-outline-success-custom,
        .btn-success-custom,
        .btn-info-custom {
            padding: 0.4rem 0.7rem;
            font-size: 0.8rem;
        }
    }
</style>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->total() }}</div>
            <div class="summary-label">Total Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_active', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Active Products</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_archived', true)->count() }}</div>
            <div class="summary-label">Archived</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="summary-card">
            <div class="summary-number">{{ $products->where('is_featured', true)->where('is_archived', false)->count() }}</div>
            <div class="summary-label">Featured</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3 position-relative">
                        <label for="search" class="form-label fw-bold">Search Products</label>
                        <input type="text" class="form-control" id="search" name="search" 
                            value="{{ request('search') }}" placeholder="Search by name, description, or SKU...">
                        <div class="search-loading" id="searchLoading">
                            <div class="spinner-border spinner-border-sm text-success" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="brand_id" class="form-label fw-bold">Brand</label>
                        <select class="form-select" id="brand_id" name="brand_id">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="category_id" class="form-label fw-bold">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select class="form-select" id="status" name="status">
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status', 'active') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="per_page" class="form-label fw-bold">Items per page</label>
                        <select class="form-select" id="per_page" name="per_page">
                            @foreach([5, 10, 15, 25, 50] as $option)
                                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- CSV Upload Modal -->
<div class="modal fade" id="csvUploadModal" tabindex="-1" aria-labelledby="csvUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-csv me-2"></i>Upload Products via CSV
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.import.csv') }}" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                @csrf
                <div class="modal-body">
                    
                    <!-- Template Download Section -->
                    <div class="template-download">
                        <h6 class="text-success mb-3">
                            <i class="fas fa-download me-2"></i>Download CSV Template
                        </h6>
                        <p class="text-muted mb-3">
                            Use our template to ensure your CSV file has the correct format.
                        </p>
                        <a href="{{ route('admin.products.csv.template') }}" class="btn btn-success-custom btn-sm">
                            <i class="fas fa-file-download me-2"></i>Download Template
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="csv-instructions">
                        <h6><i class="fas fa-info-circle me-2"></i>CSV Format Instructions</h6>
                        <ul class="small mb-0">
                            <li>File must be in CSV format (Comma Separated Values)</li>
                            <li>First row must contain column headers</li>
                            <li>Required columns: <code>name</code>, <code>sku</code>, <code>price</code>, <code>category_id</code></li>
                            <li>Optional columns: <code>description</code>, <code>brand_id</code>, <code>stock_quantity</code>, <code>image_url</code></li>
                            <li>Make sure SKU values are unique</li>
                            <li>Category ID must exist in your categories table</li>
                        </ul>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="csv_file" class="form-label fw-bold">Select CSV File</label>
                        <input type="file" class="form-control search-box" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">
                            Only .csv files are allowed. Maximum file size: 10MB
                        </div>
                    </div>

                    <!-- Processing Options -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Duplicate Handling</label>
                                <select class="form-select search-box" name="duplicate_handling">
                                    <option value="skip">Skip duplicates (keep existing)</option>
                                    <option value="update">Update existing products</option>
                                    <option value="overwrite">Overwrite existing products</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Default Status</label>
                                <select class="form-select search-box" name="default_status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar (Hidden by default) -->
                    <div class="progress mb-3" style="height: 20px; display: none;" id="uploadProgress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="progress-text">0%</span>
                        </div>
                    </div>

                    <!-- Upload Status Messages -->
                    <div id="uploadStatus" class="alert" style="display: none;"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-import-csv" id="uploadCsvBtn">
                        <i class="fas fa-upload me-2"></i>Upload CSV
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content view-modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewProductModalLabel">Product Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Product Image and Basic Info -->
                    <div class="col-md-2 text-center">
                        <div class="view-avatar-large">
                            <img src="" alt="Product Image" id="viewProductImage" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="view-customer-name" id="viewProductName">Product Name</div>
                        <div class="view-customer-id" id="viewProductId">Product ID: #123</div>
                        <div id="viewProductStatus" class="status-text status-text-active">
                            Active
                        </div>
                    </div>

                    <!-- Information Cards Grid -->
                    <div class="col-md-10">
                        <div class="view-cards-grid">
                            <!-- Product Details Card -->
                            <div class="view-info-card">
                                <label class="form-label">
                                    <i class="fas fa-info-circle me-2"></i>Product Information
                                </label>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Product ID:</span>
                                    <span class="view-detail-value" id="viewProductIdField">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Name:</span>
                                    <span class="view-detail-value" id="viewProductNameField">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">SKU:</span>
                                    <span class="view-detail-value" id="viewProductSkuField">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Description:</span>
                                    <span class="view-detail-value" id="viewProductDescription">-</span>
                                </div>
                            </div>

                            <!-- Category & Brand Card -->
                            <div class="view-info-card">
                                <label class="form-label">
                                    <i class="fas fa-tags me-2"></i>Category & Brand
                                </label>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Category:</span>
                                    <span class="view-detail-value" id="viewProductCategory">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Brand:</span>
                                    <span class="view-detail-value" id="viewProductBrand">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Featured:</span>
                                    <span class="view-detail-value" id="viewProductFeatured">-</span>
                                </div>
                            </div>

                            <!-- Pricing Information Card -->
                            <div class="view-info-card">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign me-2"></i>Pricing Information
                                </label>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Base Price:</span>
                                    <span class="view-detail-value" id="viewProductBasePrice">-</span>
                                </div>
                                <div class="view-detail-item" id="viewSalePriceRow" style="display: none;">
                                    <span class="view-detail-label">Sale Price:</span>
                                    <span class="view-detail-value" id="viewProductSalePrice">-</span>
                                </div>
                                <div class="view-detail-item" id="viewDiscountRow" style="display: none;">
                                    <span class="view-detail-label">Discount:</span>
                                    <span class="view-detail-value" id="viewProductDiscount">-</span>
                                </div>
                            </div>

                            <!-- Inventory Information Card -->
                            <div class="view-info-card">
                                <label class="form-label">
                                    <i class="fas fa-warehouse me-2"></i>Inventory Information
                                </label>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Has Variants:</span>
                                    <span class="view-detail-value" id="viewProductHasVariants">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Variant Count:</span>
                                    <span class="view-detail-value" id="viewProductVariantCount">-</span>
                                </div>
                                <div class="view-detail-item" id="viewMainStockRow" style="display: none;">
                                    <span class="view-detail-label">Stock Quantity:</span>
                                    <span class="view-detail-value" id="viewProductStockQuantity">-</span>
                                </div>
                                <div class="view-detail-item" id="viewTotalStockRow" style="display: none;">
                                    <span class="view-detail-label">Total Stock:</span>
                                    <span class="view-detail-value" id="viewProductTotalStock">-</span>
                                </div>
                            </div>

                            <!-- Timestamps Card -->
                            <div class="view-info-card">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt me-2"></i>System Information
                                </label>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Created:</span>
                                    <span class="view-detail-value" id="viewProductCreatedAt">-</span>
                                </div>
                                <div class="view-detail-item">
                                    <span class="view-detail-label">Updated:</span>
                                    <span class="view-detail-value" id="viewProductUpdatedAt">-</span>
                                </div>
                            </div>

                            <!-- Variants Card (Full Width) -->
                            <div class="view-info-card" id="viewVariantsSection" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-layer-group me-2"></i>Product Variants
                                </label>
                                <div id="viewVariantsList">
                                    <!-- Variants will be populated here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Product Table -->
<div class="card card-custom">
    <div class="card-header card-header-custom">
        <h5 class="mb-0">Product List</h5>
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                {{-- <i class="fas fa-file-csv"></i> --}}
                Import CSV
            </button>
            <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                {{-- <i class="fas fa-plus"></i> --}}
                Add Product
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if($products->count() > 0)
            <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="id-col">ID</th>
                            <th class="image-col">Image</th>
                            <th class="name-col">Product</th>
                            <th class="brand-col">Brand</th>
                            <th class="category-col">Category</th>
                            <th class="variants-col">Variants</th>
                            <th class="price-col">Price</th>
                            <th class="stock-col">Stock</th>
                            <th class="status-col">Status</th>
                            <th class="action-col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="id-col">
                                <span class="text-muted">#{{ $product->id }}</span>
                            </td>
                            <td class="image-col">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="product-img">
                            </td>
                            <td class="name-col">
                                <div class="text-center">
                                    <div class="product-name">{{ Str::limit($product->name, 30) }}</div>
                                    <div class="product-sku">{{ $product->sku }}</div>
                                </div>
                            </td>
                            <td class="brand-col">
                                @if($product->brand_id && $product->brand)
                                    <span>{{ Str::limit($product->brand->name, 15) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="category-col">
                                <span>{{ Str::limit($product->category->name, 15) }}</span>
                                @if(!$product->category->is_active)
                                    <div class="text-warning small mt-1">Inactive</div>
                                @endif
                            </td>
                            <td class="variants-col">
                                @if($product->has_variants && $product->variants && $product->variants->count() > 0)
                                    <span>{{ $product->variants->count() }}</span>
                                @else
                                    <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="price-col">
                                @if($product->has_discount)
                                    <div class="price-current">₱{{ number_format($product->sale_price, 2, '.', ',') }}</div>
                                    <div class="price-original">₱{{ number_format($product->price, 2, '.', ',') }}</div>
                                @else
                                    <div class="price-current">₱{{ number_format($product->price, 2, '.', ',') }}</div>
                                @endif
                            </td>
                            <td class="stock-col">
                                @if($product->has_variants)
                                    <span class="stock-high">{{ $product->total_stock }}</span>
                                @else
                                    @if($product->stock_quantity > 10)
                                        <span class="stock-high">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="stock-low">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="stock-out">{{ $product->stock_quantity }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="status-col">
                                @if($product->is_archived)
                                    <span class="status-text status-text-archived">Archived</span>
                                @else
                                    @if($product->is_effectively_inactive)
                                        <span class="status-text status-text-inactive">Inactive</span>
                                    @else
                                        <span class="status-text status-text-active">Active</span>
                                    @endif
                                    @if($product->is_featured)
                                        <div class="status-featured small mt-1">Featured</div>
                                    @endif
                                @endif
                            </td>
                            <td class="action-col">
                                <div class="action-buttons">
                                    <button class="action-btn btn-view viewBtn" data-bs-toggle="modal"
                                        data-bs-target="#viewProductModal" 
                                        data-product='{{ json_encode(array_merge($product->toArray(), ['image_url' => $product->image_url, 'variants' => $product->variants->map(function($variant) { return array_merge($variant->toArray(), ['image_url' => $variant->image_url]); })->toArray()])) }}'
                                        data-title="View Product" title="View Product">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="action-btn btn-edit" 
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($product->is_archived)
                                        <button class="action-btn btn-unarchive unarchiveBtn" data-id="{{ $product->slug }}"
                                            title="Unarchive Product">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    @else
                                        <button class="action-btn btn-archive archiveBtn" data-id="{{ $product->slug }}"
                                            title="Archive Product">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            @if($products->hasPages())
            <div class="d-flex justify-content-center p-3">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
            @endif
        @else
            <div class="empty-state p-5">
                <i class="fas fa-box"></i>
                <h5 class="text-muted">No Products Found</h5>
                <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                <div class="d-flex gap-3 justify-content-center">
                    {{-- <button class="btn btn-import-csv" data-bs-toggle="modal" data-bs-target="#csvUploadModal">
                        <i class="fas fa-file-csv"></i>
                        Import CSV
                    </button>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-add-product">
                        <i class="fas fa-plus"></i>
                        Add First Product
                    </a> --}}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Custom Confirmation Dialog -->
<div id="customConfirmDialog" class="custom-confirm-overlay" style="display: none;">
    <div class="custom-confirm-dialog">
        <div class="custom-confirm-header">
            <i class="fas fa-question-circle custom-confirm-icon"></i>
            <h3 class="custom-confirm-title">Confirm Action</h3>
        </div>
        <div class="custom-confirm-body">
            <p class="custom-confirm-message"></p>
        </div>
        <div class="custom-confirm-buttons">
            <button type="button" class="custom-confirm-btn custom-confirm-btn-cancel">
                <i class="fas fa-times"></i>
                <span>Cancel</span>
            </button>
            <button type="button" class="custom-confirm-btn custom-confirm-btn-confirm">
                <i class="fas fa-check"></i>
                <span>Confirm</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    const brandSelect = document.getElementById('brand_id');
    const categorySelect = document.getElementById('category_id');
    const statusSelect = document.getElementById('status');
    const perPageSelect = document.getElementById('per_page');
    
    let searchTimeout;

    // Auto-submit search with delay
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 800);
    });

    // Auto-submit brand filter immediately
    brandSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // Auto-submit other filters immediately
    categorySelect.addEventListener('change', function() {
        filterForm.submit();
    });

    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    perPageSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    // CSV Upload functionality
    const csvUploadForm = document.getElementById('csvUploadForm');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadCsvBtn = document.getElementById('uploadCsvBtn');
    const progressBar = uploadProgress.querySelector('.progress-bar');
    const progressText = uploadProgress.querySelector('.progress-text');

    if (csvUploadForm) {
        csvUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Show progress bar
            uploadProgress.style.display = 'block';
            uploadStatus.style.display = 'none';
            uploadCsvBtn.disabled = true;
            uploadCsvBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            
            // Simulate progress
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                if (progress <= 100) {
                    progressBar.style.width = progress + '%';
                    progressBar.setAttribute('aria-valuenow', progress);
                    progressText.textContent = progress + '%';
                }
            }, 100);
            
            // Submit the form
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressBar.style.width = '100%';
                progressText.textContent = '100%';
                
                if (data.success) {
                    showUploadStatus('success', data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showUploadStatus('danger', data.message || 'Upload failed. Please check your CSV file.');
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                showUploadStatus('danger', 'Upload failed: ' + error.message);
            })
            .finally(() => {
                uploadCsvBtn.disabled = false;
                uploadCsvBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload CSV';
            });
        });
        
        function showUploadStatus(type, message) {
            uploadStatus.className = `alert alert-${type}`;
            uploadStatus.innerHTML = message;
            uploadStatus.style.display = 'block';
        }
        
        // Reset form when modal is closed
        document.getElementById('csvUploadModal').addEventListener('hidden.bs.modal', function() {
            csvUploadForm.reset();
            uploadProgress.style.display = 'none';
            uploadStatus.style.display = 'none';
            progressBar.style.width = '0%';
            progressText.textContent = '0%';
        });
    }

    /* === View Product === */
    document.querySelectorAll('.viewBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const product = JSON.parse(this.dataset.product);

            // Image handling for avatar style
            const productImage = document.getElementById('viewProductImage');
            const imageUrl = product.image_url || '/images/noproduct.png';
            
            productImage.src = imageUrl;
            productImage.alt = product.name || 'Product Image';
            document.getElementById('viewProductName').textContent = product.name || 'N/A';
            document.getElementById('viewProductId').textContent = `Product ID: #${product.id}`;

            // Populate status
            const statusElement = document.getElementById('viewProductStatus');
            if (product.is_archived) {
                statusElement.className = 'status-text status-text-archived';
                statusElement.innerHTML = 'Archived';
            } else if (product.is_effectively_inactive) {
                statusElement.className = 'status-text status-text-inactive';
                statusElement.innerHTML = 'Inactive';
            } else {
                statusElement.className = 'status-text status-text-active';
                statusElement.innerHTML = 'Active';
            }

            // Handle featured status
            if (product.is_featured) {
                statusElement.innerHTML += ' <i class="fas fa-star text-warning"></i>';
            }

            // Handle variants
            const hasVariants = product.has_variants;
            const variantsArray = product.variants && ((Array.isArray(product.variants) ? product.variants : (product.variants.data || [])));
            const variantsCount = variantsArray ? variantsArray.length : 0;

            // Handle variants display
            const variantsSection = document.getElementById('viewVariantsSection');
            const variantsList = document.getElementById('viewVariantsList');
            variantsList.innerHTML = '';
            
            if (variantsCount > 0) {
                // Show the variants section
                variantsSection.style.display = 'block';
                
                // Use the variantsArray which we already prepared
                const variants = variantsArray;
                
                variants.forEach((variant, index) => {
                    const variantDiv = document.createElement('div');
                    variantDiv.className = 'view-variant-item';
                    
                    // Handle variant pricing
                    const price = parseFloat(variant.price || variant.current_price || 0);
                    const salePrice = parseFloat(variant.sale_price || 0);
                    const hasVariantDiscount = salePrice > 0 && salePrice < price;
                    const discountPercentage = hasVariantDiscount ? 
                        Math.round(((price - salePrice) / price) * 100) : 0;
                    
                    const currentPrice = hasVariantDiscount ? salePrice : price;
                    const discountText = hasVariantDiscount ? 
                        `<div class="view-variant-discount">
                            <i class="fas fa-tag me-1"></i>
                            ₱${price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} → ₱${salePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} (${discountPercentage}% off)
                        </div>` : '';
                    
                    // Handle variant stock
                    const stockText = variant.stock_quantity ? 
                        `<div class="view-variant-stock">
                            <i class="fas fa-boxes me-1"></i>
                            Stock: ${variant.stock_quantity}
                        </div>` : '';
                    
                    // Get variant image URL
                    const variantImageUrl = variant.image_url || '/images/noproduct.png';
                    
                    variantDiv.innerHTML = `
                        <div class="view-variant-content">
                            <div class="view-variant-image">
                                <img src="${variantImageUrl}" alt="${variant.variant_name || variant.name || 'Variant'}" 
                                     class="variant-img" onerror="this.src='/images/noproduct.png'">
                            </div>
                            <div class="view-variant-details">
                                <div class="view-variant-name">
                                    <i class="fas fa-cube me-2"></i>
                                    ${variant.variant_name || variant.name || 'Variant ' + (index + 1)}
                                </div>
                                <div class="view-variant-id">
                                    <small class="text-muted">ID: #${variant.id}</small>
                                </div>
                                <div class="view-variant-price">
                                    <i class="fas fa-peso-sign me-1"></i>
                                    ₱${currentPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                                </div>
                                ${discountText}
                                ${stockText}
                            </div>
                        </div>
                    `;
                    variantsList.appendChild(variantDiv);
                });
                
                console.log('Variants displayed successfully');
            } else {
                // Show message when no variants found
                variantsSection.style.display = 'block';
                variantsList.innerHTML = `
                    <div class="view-no-variants">
                        <div class="no-variants-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="no-variants-text">
                            <h5>No Variants Available</h5>
                            <p>This product does not have any variants. All inventory is managed under the main product.</p>
                        </div>
                    </div>
                `;
                console.log('No variants found, showing message');
            }

            // Populate product information
            document.getElementById('viewProductIdField').textContent = `#${product.id}`;
            document.getElementById('viewProductNameField').textContent = product.name || 'N/A';
            document.getElementById('viewProductDescription').textContent = product.description || 'No description available';
            document.getElementById('viewProductSkuField').textContent = product.sku || 'N/A';
            document.getElementById('viewProductCreatedAt').textContent = product.created_at ? 
                new Date(product.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) : 'N/A';
            document.getElementById('viewProductUpdatedAt').textContent = product.updated_at ? 
                new Date(product.updated_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                }) : 'N/A';

            // Populate category and brand
            document.getElementById('viewProductCategory').textContent = product.category ? product.category.name : 'No category';
            document.getElementById('viewProductBrand').textContent = product.brand ? product.brand.name : 'No brand';
            document.getElementById('viewProductFeatured').textContent = product.is_featured ? 'Yes' : 'No';

            // Ensure pricing information is populated
            const basePrice = parseFloat(product.price || 0);
            const salePrice = parseFloat(product.sale_price || 0);
            const hasDiscount = product.has_discount && salePrice > 0 && salePrice < basePrice;
            
            // Always show base price and sale price
            document.getElementById('viewSalePriceRow').style.display = 'flex';
            document.getElementById('viewProductBasePrice').textContent = `₱${basePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            
            if (hasDiscount) {
                const discountPercentage = Math.round(((basePrice - salePrice) / basePrice) * 100);
                document.getElementById('viewDiscountRow').style.display = 'flex';
                document.getElementById('viewProductSalePrice').textContent = `₱${salePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                document.getElementById('viewProductDiscount').textContent = `${discountPercentage}%`;
            } else {
                document.getElementById('viewDiscountRow').style.display = 'none';
                document.getElementById('viewProductSalePrice').textContent = salePrice > 0 ? `₱${salePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : 'No sale price';
            }

            // Ensure inventory information is populated
            document.getElementById('viewProductHasVariants').textContent = hasVariants ? 'Yes' : 'No';
            document.getElementById('viewProductVariantCount').textContent = variantsCount.toString();

            // Show/hide stock rows based on variants
            if (hasVariants) {
                document.getElementById('viewMainStockRow').style.display = 'none';
                document.getElementById('viewTotalStockRow').style.display = 'flex';
                document.getElementById('viewProductTotalStock').textContent = product.total_stock || '0';
            } else {
                document.getElementById('viewMainStockRow').style.display = 'flex';
                document.getElementById('viewTotalStockRow').style.display = 'none';
                document.getElementById('viewProductStockQuantity').textContent = product.stock_quantity || '0';
            }


        });
    });



    /* === Custom Confirmation Dialog Functions === */
    function showCustomConfirm(message, callback, options = {}) {
        const dialog = document.getElementById('customConfirmDialog');
        const messageEl = dialog.querySelector('.custom-confirm-message');
        const titleEl = dialog.querySelector('.custom-confirm-title');
        const iconEl = dialog.querySelector('.custom-confirm-icon');
        const confirmBtn = dialog.querySelector('.custom-confirm-btn-confirm');
        const cancelBtn = dialog.querySelector('.custom-confirm-btn-cancel');
        
        // Set options
        const config = {
            title: options.title || 'Confirm Action',
            message: message,
            confirmText: options.confirmText || 'Confirm',
            cancelText: options.cancelText || 'Cancel',
            icon: options.icon || 'fa-question-circle',
            isDanger: options.isDanger || false
        };
        
        // Update dialog content
        titleEl.textContent = config.title;
        messageEl.textContent = config.message;
        iconEl.className = `fas ${config.icon} custom-confirm-icon`;
        confirmBtn.querySelector('span').textContent = config.confirmText;
        cancelBtn.querySelector('span').textContent = config.cancelText;
        
        // Update confirm button style
        if (config.isDanger) {
            confirmBtn.classList.add('danger');
        } else {
            confirmBtn.classList.remove('danger');
        }
        
        // Show dialog
        dialog.style.display = 'flex';
        
        // Handle confirm
        const confirmHandler = () => {
            dialog.style.display = 'none';
            callback(true);
            cleanup();
        };
        
        // Handle cancel
        const cancelHandler = () => {
            dialog.style.display = 'none';
            callback(false);
            cleanup();
        };
        
        // Handle backdrop click
        const backdropHandler = (e) => {
            if (e.target === dialog) {
                cancelHandler();
            }
        };
        
        // Handle escape key
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                cancelHandler();
            }
        };
        
        // Cleanup function
        const cleanup = () => {
            confirmBtn.removeEventListener('click', confirmHandler);
            cancelBtn.removeEventListener('click', cancelHandler);
            dialog.removeEventListener('click', backdropHandler);
            document.removeEventListener('keydown', escapeHandler);
        };
        
        // Add event listeners
        confirmBtn.addEventListener('click', confirmHandler);
        cancelBtn.addEventListener('click', cancelHandler);
        dialog.addEventListener('click', backdropHandler);
        document.addEventListener('keydown', escapeHandler);
    }

    /* === Archive Product === */
    document.querySelectorAll('.archiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            showCustomConfirm(
                'Are you sure you want to archive this product? This will make it inactive but preserve its data.',
                (confirmed) => {
                    if (!confirmed) return;

                    const id = this.dataset.id;
                    const button = this;

                    // Disable button during processing
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    console.log('Archiving product with ID:', id);
                    console.log('CSRF Token:', '{{ csrf_token() }}');

                    fetch(`/admin/products/${id}/archive`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                showToast('Product archived successfully!', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showToast('Failed to archive product: ' + (data.message || 'Unknown error'), 'error');
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-archive"></i>';
                            }
                        })
                        .catch(error => {
                            console.error('Archive error:', error);
                            showToast('Network error. Please try again. Error details: ' + error.message, 'error');
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-archive"></i>';
                        });
                },
                {
                    title: 'Archive Product',
                    confirmText: 'Archive',
                    cancelText: 'Cancel',
                    icon: 'fa-archive',
                    isDanger: true
                }
            );
        }.bind(btn));
    });

    /* === Unarchive Product === */
    document.querySelectorAll('.unarchiveBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            showCustomConfirm(
                'Are you sure you want to unarchive this product? It will become active again.',
                (confirmed) => {
                    if (!confirmed) return;

                    const id = this.dataset.id;
                    const button = this;

                    // Disable button during processing
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    console.log('Unarchiving product with ID:', id);
                    console.log('CSRF Token:', '{{ csrf_token() }}');

                    fetch(`/admin/products/${id}/unarchive`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (data.success) {
                                showToast('Product unarchived successfully!', 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                showToast('Failed to unarchive product: ' + (data.message || 'Unknown error'), 'error');
                                button.disabled = false;
                                button.innerHTML = '<i class="fas fa-box-open"></i>';
                            }
                        })
                        .catch(error => {
                            console.error('Unarchive error:', error);
                            showToast('Network error. Please try again. Error details: ' + error.message, 'error');
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-box-open"></i>';
                        });
                },
                {
                    title: 'Unarchive Product',
                    confirmText: 'Unarchive',
                    cancelText: 'Cancel',
                    icon: 'fa-box-open',
                    isDanger: false
                }
            );
        }.bind(btn));
    });
});

// Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toasts
    document.querySelectorAll('.upper-middle-toast').forEach(toast => toast.remove());
    
    const bgColors = {
        'success': '#2C8F0C',
        'error': '#dc3545',
        'warning': '#ffc107',
        'info': '#17a2b8'
    };
    
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-triangle',
        'warning': 'fa-exclamation-circle',
        'info': 'fa-info-circle'
    };
    
    const bgColor = bgColors[type] || bgColors.success;
    const icon = icons[type] || icons.success;
    const textColor = type === 'warning' ? 'text-dark' : 'text-white';
    
    const toast = document.createElement('div');
    toast.className = 'upper-middle-toast position-fixed start-50 translate-middle-x p-3';
    toast.style.cssText = `
        top: 100px;
        z-index: 9999;
        min-width: 300px;
        text-align: center;
    `;
    
    toast.innerHTML = `
        <div class="toast align-items-center border-0 show shadow-lg" role="alert" style="background-color: ${bgColor}; border-radius: 10px;">
            <div class="d-flex justify-content-center align-items-center p-3">
                <div class="toast-body ${textColor} d-flex align-items-center">
                    <i class="fas ${icon} me-2 fs-5"></i>
                    <span class="fw-semibold">${message}</span>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endpush
@endsection