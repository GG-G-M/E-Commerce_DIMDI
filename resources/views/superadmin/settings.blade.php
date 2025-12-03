@extends('layouts.superadmin')

@section('content')
<style>
    /* Enhanced Design - Same Structure, Better Visuals */
    .card-custom {
        border: none;
        border-radius: 18px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        overflow: hidden;
        position: relative;
    }

    .card-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card-custom:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
    }

    .card-custom:hover::before {
        opacity: 1;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .card-header-custom::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        transform: rotate(30deg);
        animation: shine 3s infinite;
    }

    @keyframes shine {
        0% { transform: rotate(30deg) translateX(-100%); }
        100% { transform: rotate(30deg) translateX(100%); }
    }

    .card-header-custom h5 {
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .card-header-custom h5 i {
        font-size: 1.4rem;
        background: rgba(255,255,255,0.2);
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }

    /* Enhanced Stat Cards */
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 2rem 1.5rem;
        border: 1px solid rgba(102, 126, 234, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color-1), var(--card-color-2));
        border-radius: 18px 18px 0 0;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        border-color: rgba(102, 126, 234, 0.3);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, var(--card-color-1), var(--card-color-2));
        color: white;
        box-shadow: 0 10px 20px rgba(var(--card-color-rgb), 0.2);
    }

    .stat-value {
        font-size: 3rem;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1;
        margin-bottom: 0.5rem;
        font-family: 'Inter', sans-serif;
    }

    .stat-label {
        color: #666;
        font-size: 0.95rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Settings Card Styles */
    .settings-card {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        border: 1px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .settings-card:hover {
        border-color: rgba(102, 126, 234, 0.3);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
        transform: translateY(-3px);
    }

    .settings-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        color: #667eea;
    }

    .settings-title {
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #1a1a2e;
        font-size: 1.2rem;
    }

    .settings-desc {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    /* Settings Modal Styles */
    .settings-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .settings-modal .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .settings-modal .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .settings-modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.8;
    }

    .settings-modal .modal-body {
        padding: 2rem;
    }

    .settings-modal .modal-footer {
        border-top: 1px solid rgba(102, 126, 234, 0.1);
        padding: 1.5rem 2rem;
        background: #f8f9ff;
        border-radius: 0 0 20px 20px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #1a1a2e;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: #667eea;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15);
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-label {
        font-weight: 500;
        color: #4a5568;
    }

    .form-text {
        color: #718096;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    /* Enhanced Table */
    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(102, 126, 234, 0.1);
        background: white;
    }

    .table {
        margin-bottom: 0;
        background: white;
    }

    .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        padding: 1.5rem 2rem;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table tbody td {
        padding: 1.5rem 2rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(102, 126, 234, 0.05);
        transition: all 0.3s ease;
    }

    .table tbody tr {
        transition: all 0.3s ease;
        position: relative;
    }

    .table tbody tr::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.02), rgba(118, 75, 162, 0.02));
        transform: translateX(4px);
    }

    .table tbody tr:hover td {
        background: transparent;
    }

    /* Enhanced User Icon */
    .user-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        flex-shrink: 0;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .user-icon:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    /* Enhanced Badges */
    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .role-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .badge-superadmin {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .badge-admin {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .badge-delivery {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: white;
    }

    .badge-customer {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .badge-checker {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        color: #333;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .status-active {
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        color: #2e7d32;
        border: 2px solid #c8e6c9;
    }

    .status-inactive {
        background: linear-gradient(135deg, #ffebee, #ffcdd2);
        color: #c62828;
        border: 2px solid #ffcdd2;
    }

    /* Enhanced Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn-icon:hover::before {
        left: 100%;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-edit {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 10px 20px rgba(245, 87, 108, 0.3);
    }

    /* Enhanced Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .quick-action-card {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        border: 2px solid transparent;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .quick-action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }

    .quick-action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .quick-action-card:hover::before {
        transform: translateY(0);
    }

    .quick-action-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        color: #667eea;
        transition: all 0.3s ease;
    }

    .quick-action-card:hover .quick-action-icon {
        transform: scale(1.1) rotate(5deg);
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .quick-action-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #1a1a2e;
        font-size: 1.2rem;
    }

    .quick-action-desc {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Enhanced System Overview */
    .system-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 18px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .system-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    .system-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        position: relative;
        z-index: 1;
    }

    .system-metric:last-child {
        border-bottom: none;
    }

    .system-metric i {
        font-size: 1.8rem;
        opacity: 0.8;
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 800;
        font-family: 'Inter', sans-serif;
    }

    .metric-label {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
    }

    /* Enhanced Chart Containers */
    .chart-container {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        border: 1px solid rgba(102, 126, 234, 0.1);
        height: 100%;
        transition: all 0.3s ease;
    }

    .chart-container:hover {
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.1);
        border-color: rgba(102, 126, 234, 0.2);
    }

    .chart-title {
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a2e;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-title i {
        color: #667eea;
    }

    /* Enhanced Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 5rem;
        color: rgba(102, 126, 234, 0.1);
        margin-bottom: 1.5rem;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    .empty-state h5 {
        color: #666;
        margin-bottom: 0.75rem;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .empty-state p {
        color: #888;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Enhanced Buttons */
    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 1rem 2rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .btn-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient:hover::before {
        left: 100%;
    }

    /* Settings Button */
    .btn-settings {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-settings:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }

    /* Enhanced Pagination */
    .pagination-container {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(102, 126, 234, 0.1);
        border-radius: 0 0 18px 18px;
    }

    /* Enhanced User Info */
    .user-info-cell {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .user-name {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 4px;
        font-size: 1.1rem;
    }

    .user-email {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .last-login {
        font-size: 0.85rem;
        color: #888;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    /* Welcome Header Enhancement */
    .welcome-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 18px;
        padding: 3rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .welcome-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.3;
    }

    .welcome-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .welcome-title i {
        background: rgba(255,255,255,0.2);
        padding: 1rem;
        border-radius: 16px;
        margin-right: 1rem;
        backdrop-filter: blur(10px);
    }

    .welcome-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        font-weight: 500;
        position: relative;
        z-index: 1;
    }

    .system-time {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .system-time-value {
        font-size: 2.5rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
    }

    .system-time-label {
        font-size: 1rem;
        opacity: 0.8;
        font-weight: 500;
    }
</style>

<!-- Welcome Header - Enhanced -->
<div class="row mb-5">
    <div class="col-12">
        <div class="welcome-header">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="welcome-title">
                        <i class="fas fa-crown"></i>Super Admin Dashboard
                    </h1>
                    <p class="welcome-subtitle">
                        Welcome back, <strong>{{ auth()->user()->name }}</strong>! You have full control over the entire system.
                    </p>
                </div>
                <div class="col-lg-4">
                    <div class="system-time">
                        <div class="system-time-value">{{ date('H:i') }}</div>
                        <div class="system-time-label">System Time</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards - Enhanced with Custom Colors -->
<div class="row mb-5">
    @php
        $stats = [
            ['count' => App\Models\User::count(), 'label' => 'Total Users', 'icon' => 'users', 'color1' => '#667eea', 'color2' => '#764ba2'],
            ['count' => App\Models\User::where('role', 'super_admin')->count(), 'label' => 'Super Admins', 'icon' => 'crown', 'color1' => '#f093fb', 'color2' => '#f5576c'],
            ['count' => App\Models\User::where('role', 'admin')->count(), 'label' => 'Admins', 'icon' => 'user-shield', 'color1' => '#4facfe', 'color2' => '#00f2fe'],
            ['count' => App\Models\User::where('is_active', true)->count(), 'label' => 'Active Users', 'icon' => 'user-check', 'color1' => '#43e97b', 'color2' => '#38f9d7'],
            ['count' => App\Models\Product::count(), 'label' => 'Products', 'icon' => 'box', 'color1' => '#fa709a', 'color2' => '#fee140'],
            ['count' => App\Models\Order::count(), 'label' => 'Orders', 'icon' => 'shopping-cart', 'color1' => '#a8edea', 'color2' => '#fed6e3'],
        ];
    @endphp
    
    @foreach($stats as $stat)
    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
        <div class="stat-card" 
             style="--card-color-1: {{ $stat['color1'] }}; --card-color-2: {{ $stat['color2'] }}; --card-color-rgb: {{ hexdec(substr($stat['color1'], 1, 2)) }}, {{ hexdec(substr($stat['color1'], 3, 2)) }}, {{ hexdec(substr($stat['color1'], 5, 2)) }};">
            <div class="stat-icon">
                <i class="fas fa-{{ $stat['icon'] }}"></i>
            </div>
            <div class="stat-value">{{ number_format($stat['count']) }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<!-- Settings Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="fas fa-cog"></i>System Settings</h5>
                <button class="btn-settings" data-bs-toggle="modal" data-bs-target="#systemSettingsModal">
                    <i class="fas fa-sliders-h"></i> Configure Settings
                </button>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="settings-card">
                            <div class="settings-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h5 class="settings-title">Security Settings</h5>
                            <p class="settings-desc">Configure password policies, login security, and access controls.</p>
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#securitySettingsModal">
                                <i class="fas fa-edit me-2"></i> Configure
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="settings-card">
                            <div class="settings-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h5 class="settings-title">Notification Settings</h5>
                            <p class="settings-desc">Manage email notifications, alerts, and system announcements.</p>
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#notificationSettingsModal">
                                <i class="fas fa-edit me-2"></i> Configure
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="settings-card">
                            <div class="settings-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h5 class="settings-title">Appearance Settings</h5>
                            <p class="settings-desc">Customize themes, colors, and dashboard appearance.</p>
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#appearanceSettingsModal">
                                <i class="fas fa-edit me-2"></i> Configure
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="settings-card">
                            <div class="settings-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h5 class="settings-title">System Maintenance</h5>
                            <p class="settings-desc">Backup, restore, and system optimization tools.</p>
                            <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#maintenanceSettingsModal">
                                <i class="fas fa-edit me-2"></i> Configure
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions - Enhanced -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="fas fa-bolt"></i>Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="quick-actions-grid">
                    @php
                        $quickActions = [
                            ['route' => 'superadmin.users.create', 'icon' => 'user-plus', 'title' => 'Create Admin', 'desc' => 'Add new system administrator'],
                            ['route' => 'superadmin.users.index', 'icon' => 'users-cog', 'title' => 'Manage Users', 'desc' => 'View and control all users'],
                            ['route' => 'admin.dashboard', 'icon' => 'user-shield', 'title' => 'Admin Panel', 'desc' => 'Switch to admin interface'],
                            ['route' => 'home', 'icon' => 'store', 'title' => 'Visit Store', 'desc' => 'View store as customer'],
                            ['route' => 'admin.products.index', 'icon' => 'boxes', 'title' => 'Products', 'desc' => 'Manage product catalog'],
                            ['route' => 'admin.orders.index', 'icon' => 'clipboard-list', 'title' => 'Orders', 'desc' => 'Monitor all orders'],
                        ];
                    @endphp
                    
                    @foreach($quickActions as $action)
                    <a href="{{ route($action['route']) }}" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-{{ $action['icon'] }}"></i>
                        </div>
                        <div class="quick-action-title">{{ $action['title'] }}</div>
                        <div class="quick-action-desc">{{ $action['desc'] }}</div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Users & System Overview - Enhanced -->
<div class="row mb-5">
    <!-- Recent Users - Enhanced -->
    <div class="col-lg-8 mb-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="fas fa-history"></i>Recent Users</h5>
                <a href="{{ route('superadmin.users.index') }}" class="btn-gradient btn-sm">
                    <i class="fas fa-eye me-1"></i> View All
                </a>
            </div>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>User Details</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Created</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(App\Models\User::orderBy('created_at', 'desc')->take(8)->get() as $user)
                            <tr>
                                <td class="text-center fw-bold">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="user-info-cell">
                                        <div class="user-icon" style="background: linear-gradient(135deg, 
                                            {{ $user->role == 'super_admin' ? '#f093fb 0%, #f5576c 100%' : 
                                              ($user->role == 'admin' ? '#4facfe 0%, #00f2fe 100%' :
                                              ($user->role == 'delivery' ? '#43e97b 0%, #38f9d7 100%' : '#fa709a 0%, #fee140 100%')) }});">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">
                                                <i class="fas fa-envelope"></i>
                                                {{ $user->email }}
                                            </div>
                                            @if($user->last_login_at)
                                            <div class="last-login">
                                                <i class="fas fa-sign-in-alt"></i>
                                                {{ $user->last_login_at->diffForHumans() }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="role-badge 
                                        {{ $user->role == 'super_admin' ? 'badge-superadmin' : 
                                           ($user->role == 'admin' ? 'badge-admin' :
                                           ($user->role == 'delivery' ? 'badge-delivery' :
                                           ($user->role == 'stock_checker' || $user->role == 'checker' ? 'badge-checker' : 'badge-customer'))) }}">
                                        <i class="fas fa-{{ $user->role == 'super_admin' ? 'crown' : 
                                            ($user->role == 'admin' ? 'user-shield' :
                                            ($user->role == 'delivery' ? 'truck' :
                                            ($user->role == 'stock_checker' || $user->role == 'checker' ? 'clipboard-check' : 'user'))) }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                        <i class="fas fa-{{ $user->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center fw-semibold" style="color: #666;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('superadmin.users.show', $user) }}" class="btn-icon btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn-icon btn-edit" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-delete" title="Delete" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash"></i>
                                        <h5>No Users Found</h5>
                                        <p>Start by adding your first user</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="pagination-container">
                    <a href="{{ route('superadmin.users.index') }}" class="btn-gradient w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-users me-2"></i> 
                        <span>View All Users ({{ number_format(App\Models\User::count()) }})</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview - Enhanced -->
    <div class="col-lg-4 mb-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <h5><i class="fas fa-chart-pie"></i>System Overview</h5>
            </div>
            <div class="card-body">
                <div class="system-card mb-4">
                    <div class="system-metric">
                        <div>
                            <div class="metric-value">{{ App\Models\User::where('role', 'customer')->count() }}</div>
                            <div class="metric-label">Total Customers</div>
                        </div>
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="system-metric">
                        <div>
                            <div class="metric-value">{{ App\Models\User::where('role', 'delivery')->count() }}</div>
                            <div class="metric-label">Delivery Staff</div>
                        </div>
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="system-metric">
                        <div>
                            <div class="metric-value">{{ App\Models\User::where('role', 'stock_checker')->orWhere('role', 'checker')->count() }}</div>
                            <div class="metric-label">Stock Checkers</div>
                        </div>
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>

                <div class="chart-container mb-4">
                    <div class="chart-title">
                        <i class="fas fa-chart-bar"></i> User Status
                    </div>
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));">
                                <h2 class="fw-bold mb-2" style="color: #2e7d32;">{{ App\Models\User::where('is_active', true)->count() }}</h2>
                                <small class="text-muted fw-semibold">Active Users</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(244, 67, 54, 0.05));">
                                <h2 class="fw-bold mb-2" style="color: #c62828;">{{ App\Models\User::where('is_active', false)->count() }}</h2>
                                <small class="text-muted fw-semibold">Inactive Users</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(33, 150, 243, 0.05));">
                                <h2 class="fw-bold mb-2" style="color: #1565c0;">{{ App\Models\Order::count() }}</h2>
                                <small class="text-muted fw-semibold">Total Orders</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-3" style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 152, 0, 0.05));">
                                <h2 class="fw-bold mb-2" style="color: #ef6c00;">{{ App\Models\Product::where('stock_quantity', '<', 10)->count() }}</h2>
                                <small class="text-muted fw-semibold">Low Stock</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <div class="chart-title">
                        <i class="fas fa-chart-line"></i> Quick Statistics
                    </div>
                    <div class="list-group list-group-flush">
                        @php
                            $quickStats = [
                                ['icon' => 'clock', 'color' => 'info', 'label' => 'Last 24 Hours', 'count' => App\Models\User::where('created_at', '>=', now()->subDay())->count()],
                                ['icon' => 'calendar-week', 'color' => 'warning', 'label' => 'Last 7 Days', 'count' => App\Models\User::where('created_at', '>=', now()->subDays(7))->count()],
                                ['icon' => 'calendar-alt', 'color' => 'success', 'label' => 'Last 30 Days', 'count' => App\Models\User::where('created_at', '>=', now()->subDays(30))->count()],
                                ['icon' => 'user-clock', 'color' => 'purple', 'label' => 'Never Logged In', 'count' => App\Models\User::whereNull('last_login_at')->count()],
                            ];
                        @endphp
                        
                        @foreach($quickStats as $stat)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-0">
                            <span class="d-flex align-items-center">
                                <i class="fas fa-{{ $stat['icon'] }} text-{{ $stat['color'] }} me-3" style="font-size: 1.2rem;"></i>
                                <span class="fw-semibold">{{ $stat['label'] }}</span>
                            </span>
                            <span class="badge rounded-pill px-3 py-2 fw-bold" style="background: var(--bs-{{ $stat['color'] }});">
                                {{ $stat['count'] }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Settings Modals -->
<!-- System Settings Modal -->
<div class="modal fade settings-modal" id="systemSettingsModal" tabindex="-1" aria-labelledby="systemSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="systemSettingsModalLabel">
                    <i class="fas fa-sliders-h me-2"></i>System Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="systemSettingsForm" method="POST" action="{{ route('superadmin.settings.update') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-globe"></i> Site Name
                                </label>
                                <input type="text" class="form-control" name="site_name" value="{{ config('app.name') }}" required>
                                <div class="form-text">The name displayed throughout the application</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i> Support Email
                                </label>
                                <input type="email" class="form-control" name="support_email" value="{{ config('mail.support_email', 'support@example.com') }}" required>
                                <div class="form-text">Email address for user support inquiries</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-language"></i> Default Language
                                </label>
                                <select class="form-select" name="default_language">
                                    <option value="en" selected>English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clock"></i> Timezone
                                </label>
                                <select class="form-select" name="timezone">
                                    <option value="UTC" selected>UTC</option>
                                    <option value="America/New_York">Eastern Time</option>
                                    <option value="America/Chicago">Central Time</option>
                                    <option value="America/Denver">Mountain Time</option>
                                    <option value="America/Los_Angeles">Pacific Time</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Date Format
                                </label>
                                <select class="form-select" name="date_format">
                                    <option value="Y-m-d" selected>YYYY-MM-DD</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="d/m/Y">DD/MM/YYYY</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-users"></i> Users Per Page
                                </label>
                                <select class="form-select" name="users_per_page">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="maintenance_mode" name="maintenance_mode">
                            <label class="form-check-label" for="maintenance_mode">
                                <i class="fas fa-tools me-2"></i> Maintenance Mode
                            </label>
                        </div>
                        <div class="form-text">When enabled, only admins can access the site</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="user_registration" name="user_registration" checked>
                            <label class="form-check-label" for="user_registration">
                                <i class="fas fa-user-plus me-2"></i> Allow User Registration
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Security Settings Modal -->
<div class="modal fade settings-modal" id="securitySettingsModal" tabindex="-1" aria-labelledby="securitySettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="securitySettingsModalLabel">
                    <i class="fas fa-shield-alt me-2"></i>Security Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="securitySettingsForm" method="POST" action="{{ route('superadmin.settings.security') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-key"></i> Password Policy
                        </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="password_min_length" name="password_min_length" checked>
                            <label class="form-check-label" for="password_min_length">
                                Minimum 8 characters
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="password_uppercase" name="password_uppercase" checked>
                            <label class="form-check-label" for="password_uppercase">
                                Require uppercase letters
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="password_numbers" name="password_numbers" checked>
                            <label class="form-check-label" for="password_numbers">
                                Require numbers
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="password_special" name="password_special">
                            <label class="form-check-label" for="password_special">
                                Require special characters
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-lock"></i> Max Login Attempts
                                </label>
                                <input type="number" class="form-control" name="max_login_attempts" value="5" min="1" max="10">
                                <div class="form-text">Number of failed attempts before lockout</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-clock"></i> Lockout Duration (minutes)
                                </label>
                                <input type="number" class="form-control" name="lockout_duration" value="15" min="1" max="1440">
                                <div class="form-text">How long to lock account after max attempts</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="two_factor_auth" name="two_factor_auth">
                            <label class="form-check-label" for="two_factor_auth">
                                <i class="fas fa-mobile-alt me-2"></i> Enable Two-Factor Authentication
                            </label>
                        </div>
                        <div class="form-text">Adds an extra layer of security for admin accounts</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="session_timeout" name="session_timeout" checked>
                            <label class="form-check-label" for="session_timeout">
                                <i class="fas fa-hourglass-half me-2"></i> Auto Logout After Inactivity
                            </label>
                        </div>
                        <div class="form-text">Automatically logout users after 30 minutes of inactivity</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-1"></i> Save Security Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notification Settings Modal -->
<div class="modal fade settings-modal" id="notificationSettingsModal" tabindex="-1" aria-labelledby="notificationSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationSettingsModalLabel">
                    <i class="fas fa-bell me-2"></i>Notification Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="notificationSettingsForm" method="POST" action="{{ route('superadmin.settings.notifications') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope"></i> Email Notifications
                        </label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email_new_user" name="email_new_user" checked>
                            <label class="form-check-label" for="email_new_user">
                                New user registrations
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email_new_order" name="email_new_order" checked>
                            <label class="form-check-label" for="email_new_order">
                                New orders
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email_low_stock" name="email_low_stock" checked>
                            <label class="form-check-label" for="email_low_stock">
                                Low stock alerts
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="email_system_alerts" name="email_system_alerts" checked>
                            <label class="form-check-label" for="email_system_alerts">
                                System alerts and errors
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-shield"></i> Admin Notification Email
                                </label>
                                <input type="email" class="form-control" name="admin_notification_email" value="{{ config('mail.admin_email', 'admin@example.com') }}">
                                <div class="form-text">Email address for admin notifications</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-slack"></i> Slack Webhook URL
                                </label>
                                <input type="url" class="form-control" name="slack_webhook_url" placeholder="https://hooks.slack.com/services/...">
                                <div class="form-text">Optional: Send notifications to Slack</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-sms"></i> SMS Notifications
                        </label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="sms_notifications" name="sms_notifications">
                            <label class="form-check-label" for="sms_notifications">
                                Enable SMS notifications for critical alerts
                            </label>
                        </div>
                        <div class="form-text">Requires SMS gateway configuration</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-1"></i> Save Notification Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Appearance Settings Modal -->
<div class="modal fade settings-modal" id="appearanceSettingsModal" tabindex="-1" aria-labelledby="appearanceSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appearanceSettingsModalLabel">
                    <i class="fas fa-palette me-2"></i>Appearance Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="appearanceSettingsForm" method="POST" action="{{ route('superadmin.settings.appearance') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-fill-drip"></i> Primary Color
                                </label>
                                <input type="color" class="form-control form-control-color" name="primary_color" value="#667eea" title="Choose primary color">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-fill"></i> Secondary Color
                                </label>
                                <input type="color" class="form-control form-control-color" name="secondary_color" value="#764ba2" title="Choose secondary color">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-paint-brush"></i> Theme
                        </label>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="theme-option text-center">
                                    <div class="theme-preview mb-2" style="background: linear-gradient(135deg, #667eea, #764ba2); height: 80px; border-radius: 12px;"></div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme" id="theme_default" value="default" checked>
                                        <label class="form-check-label" for="theme_default">
                                            Default Theme
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="theme-option text-center">
                                    <div class="theme-preview mb-2" style="background: linear-gradient(135deg, #4facfe, #00f2fe); height: 80px; border-radius: 12px;"></div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme" id="theme_blue" value="blue">
                                        <label class="form-check-label" for="theme_blue">
                                            Blue Theme
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="theme-option text-center">
                                    <div class="theme-preview mb-2" style="background: linear-gradient(135deg, #43e97b, #38f9d7); height: 80px; border-radius: 12px;"></div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="theme" id="theme_green" value="green">
                                        <label class="form-check-label" for="theme_green">
                                            Green Theme
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-desktop"></i> Dashboard Layout
                        </label>
                        <select class="form-select" name="dashboard_layout">
                            <option value="default" selected>Default Layout</option>
                            <option value="compact">Compact Layout</option>
                            <option value="spacious">Spacious Layout</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="dark_mode" name="dark_mode">
                            <label class="form-check-label" for="dark_mode">
                                <i class="fas fa-moon me-2"></i> Enable Dark Mode
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="animations" name="animations" checked>
                            <label class="form-check-label" for="animations">
                                <i class="fas fa-play-circle me-2"></i> Enable Animations
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-1"></i> Save Appearance Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Maintenance Settings Modal -->
<div class="modal fade settings-modal" id="maintenanceSettingsModal" tabindex="-1" aria-labelledby="maintenanceSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceSettingsModalLabel">
                    <i class="fas fa-database me-2"></i>System Maintenance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-database fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">Backup Database</h5>
                                <p class="card-text text-muted small">Create a backup of the entire database</p>
                                <button class="btn btn-outline-primary w-100" id="backupDatabaseBtn">
                                    <i class="fas fa-save me-2"></i> Backup Now
                                </button>
                                <div class="mt-2 small text-muted">Last backup: Never</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-trash-alt fa-3x text-warning"></i>
                                </div>
                                <h5 class="card-title">Clear Cache</h5>
                                <p class="card-text text-muted small">Clear application and view cache</p>
                                <button class="btn btn-outline-warning w-100" id="clearCacheBtn">
                                    <i class="fas fa-broom me-2"></i> Clear Cache
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-chart-line fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title">System Logs</h5>
                                <p class="card-text text-muted small">View and manage system logs</p>
                                <a href="{{ route('superadmin.logs.index') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-file-alt me-2"></i> View Logs
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-sync-alt fa-3x text-info"></i>
                                </div>
                                <h5 class="card-title">Optimize Database</h5>
                                <p class="card-text text-muted small">Optimize database tables for better performance</p>
                                <button class="btn btn-outline-info w-100" id="optimizeDatabaseBtn">
                                    <i class="fas fa-tachometer-alt me-2"></i> Optimize Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> These actions affect the entire system. Use with caution.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Activity Log - Enhanced -->
<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5><i class="fas fa-history"></i>Recent Activity</h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    @php
                        $recentActivities = [
                            ['icon' => 'user-plus', 'color' => '#43e97b', 'title' => 'New User Registration', 'desc' => 'John Doe registered as customer', 'time' => '2 mins ago'],
                            ['icon' => 'shopping-cart', 'color' => '#4facfe', 'title' => 'Order Placed', 'desc' => 'Order #ORD-1234 placed successfully', 'time' => '15 mins ago'],
                            ['icon' => 'box', 'color' => '#fa709a', 'title' => 'Low Stock Alert', 'desc' => 'Product "iPhone 15" is low in stock', 'time' => '1 hour ago'],
                            ['icon' => 'truck', 'color' => '#f093fb', 'title' => 'Delivery Started', 'desc' => 'Order #ORD-1233 out for delivery', 'time' => '2 hours ago'],
                        ];
                    @endphp
                    
                    @foreach($recentActivities as $activity)
                    <div class="col-xl-3 col-lg-6 mb-4">
                        <div class="activity-card p-4 rounded-3 border" style="border-color: rgba(102, 126, 234, 0.1) !important;">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div style="width: 50px; height: 50px; border-radius: 12px; background: {{ $activity['color'] }}20; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-{{ $activity['icon'] }}" style="color: {{ $activity['color'] }}; font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $activity['title'] }}</h6>
                                    <small class="text-muted">{{ $activity['desc'] }}</small>
                                </div>
                            </div>
                            <span class="badge px-3 py-2 fw-semibold" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                                <i class="fas fa-clock me-1"></i>{{ $activity['time'] }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Real-time clock update
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        });
        document.querySelector('.system-time-value').textContent = timeString;
    }

    // Update clock every second for smooth animation
    setInterval(updateClock, 1000);

    // Smooth counter animation for stats
    const statCards = document.querySelectorAll('.stat-value');
    statCards.forEach(stat => {
        const finalValue = parseInt(stat.textContent.replace(/,/g, ''));
        let currentValue = 0;
        const increment = finalValue / 50; // Faster animation
        const duration = 1000; // 1 second
        const stepTime = duration / 50;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                stat.textContent = finalValue.toLocaleString();
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(currentValue).toLocaleString();
            }
        }, stepTime);
    });

    // Settings Form Handling
    const settingsForms = document.querySelectorAll('.settings-modal form');
    settingsForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new FormData(this)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success message
                    showToast('Settings saved successfully!', 'success');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(this.closest('.modal'));
                    modal.hide();
                    
                    // Reload page if needed
                    if (result.reload) {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    showToast('Failed to save settings', 'error');
                }
            } catch (error) {
                showToast('Network error. Please try again.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });

    // Maintenance Actions
    document.getElementById('backupDatabaseBtn')?.addEventListener('click', async function() {
        if (!confirm('Create a database backup? This may take a moment.')) return;
        
        this.disabled = true;
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Backing up...';
        
        try {
            const response = await fetch('{{ route("superadmin.backup.create") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Database backup created successfully!', 'success');
            } else {
                showToast('Failed to create backup', 'error');
            }
        } catch (error) {
            showToast('Network error. Please try again.', 'error');
        } finally {
            this.disabled = false;
            this.innerHTML = originalText;
        }
    });

    document.getElementById('clearCacheBtn')?.addEventListener('click', async function() {
        if (!confirm('Clear all cache? This may temporarily affect performance.')) return;
        
        this.disabled = true;
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Clearing...';
        
        try {
            const response = await fetch('{{ route("superadmin.cache.clear") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Cache cleared successfully!', 'success');
            } else {
                showToast('Failed to clear cache', 'error');
            }
        } catch (error) {
            showToast('Network error. Please try again.', 'error');
        } finally {
            this.disabled = false;
            this.innerHTML = originalText;
        }
    });

    document.getElementById('optimizeDatabaseBtn')?.addEventListener('click', async function() {
        if (!confirm('Optimize database tables? This may improve performance.')) return;
        
        this.disabled = true;
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Optimizing...';
        
        try {
            const response = await fetch('{{ route("superadmin.database.optimize") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Database optimized successfully!', 'success');
            } else {
                showToast('Failed to optimize database', 'error');
            }
        } catch (error) {
            showToast('Network error. Please try again.', 'error');
        } finally {
            this.disabled = false;
            this.innerHTML = originalText;
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Theme color preview
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(input => {
        input.addEventListener('input', function() {
            const preview = this.closest('.row')?.querySelector('.theme-preview');
            if (preview && this.name.includes('primary')) {
                const secondaryColor = this.closest('.row')?.querySelector('input[name="secondary_color"]')?.value || '#764ba2';
                preview.style.background = `linear-gradient(135deg, ${this.value}, ${secondaryColor})`;
            }
        });
    });

    // Theme selection preview
    const themeRadios = document.querySelectorAll('input[name="theme"]');
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const previews = document.querySelectorAll('.theme-preview');
            previews.forEach(preview => {
                preview.style.opacity = '0.5';
            });
            
            const selectedPreview = this.closest('.theme-option').querySelector('.theme-preview');
            selectedPreview.style.opacity = '1';
        });
    });
});
</script>
@endpush