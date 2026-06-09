<?php

enum OrderStatus: string {
    case PENDING = 'PENDING';
    case SHIPPED = 'SHIPPED';
    case DELIVERED = 'DELIVERED';
    case CANCELLED = 'CANCELLED';
}