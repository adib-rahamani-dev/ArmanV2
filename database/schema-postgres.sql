CREATE TABLE IF NOT EXISTS customers (
    id VARCHAR(40) PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(190),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS orders (
    id VARCHAR(40) PRIMARY KEY,
    tracking_code VARCHAR(24) NOT NULL UNIQUE,
    customer_id VARCHAR(40) NOT NULL REFERENCES customers(id),
    source VARCHAR(24) NOT NULL DEFAULT 'purchase',
    product_id VARCHAR(80), product_title VARCHAR(190) NOT NULL,
    group_name VARCHAR(32), plan VARCHAR(120), price_snapshot VARCHAR(80),
    activation_email VARCHAR(190), notes TEXT,
    status VARCHAR(32) NOT NULL DEFAULT 'awaiting_payment',
    payment_status VARCHAR(32) NOT NULL DEFAULT 'not_submitted',
    identity_status VARCHAR(32) NOT NULL DEFAULT 'not_submitted',
    verification_token_hash CHAR(64) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS verifications (
    id VARCHAR(40) PRIMARY KEY,
    order_id VARCHAR(40) NOT NULL UNIQUE REFERENCES orders(id) ON DELETE CASCADE,
    provider VARCHAR(24) NOT NULL,
    receipt_location TEXT NOT NULL, receipt_mime VARCHAR(80) NOT NULL,
    bank_card_location TEXT NOT NULL, bank_card_mime VARCHAR(80) NOT NULL,
    national_card_location TEXT NOT NULL, national_card_mime VARCHAR(80) NOT NULL,
    national_id_ciphertext TEXT NOT NULL, card_last4 CHAR(4) NOT NULL,
    consent_at TIMESTAMPTZ NOT NULL, status VARCHAR(32) NOT NULL DEFAULT 'pending',
    reviewer_note TEXT, reviewed_at TIMESTAMPTZ, delete_after TIMESTAMPTZ NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS order_events (
    id VARCHAR(40) PRIMARY KEY, order_id VARCHAR(40) NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    event_type VARCHAR(64) NOT NULL, previous_status VARCHAR(32), new_status VARCHAR(32),
    actor VARCHAR(64) NOT NULL, metadata JSONB NOT NULL DEFAULT '{}'::jsonb,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS leads (
    id VARCHAR(40) PRIMARY KEY, full_name VARCHAR(120) NOT NULL, phone VARCHAR(20) NOT NULL,
    service VARCHAR(190), budget VARCHAR(120), message TEXT, status VARCHAR(32) NOT NULL DEFAULT 'new',
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS product_overrides (
    group_name VARCHAR(32) NOT NULL, product_id VARCHAR(80) NOT NULL, active BOOLEAN NOT NULL,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), PRIMARY KEY(group_name, product_id)
);
CREATE INDEX IF NOT EXISTS idx_orders_customer ON orders(customer_id);
CREATE INDEX IF NOT EXISTS idx_orders_status_created ON orders(status, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_verifications_status ON verifications(status, created_at DESC);
CREATE INDEX IF NOT EXISTS idx_leads_status_created ON leads(status, created_at DESC);
