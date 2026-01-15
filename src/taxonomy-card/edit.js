/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

/**
 * This package includes a library of generic WordPress components
 * to be used for creating common UI elements shared between screens and features of the WordPress dashboard.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/
 */
import { PanelBody, PanelRow, TextControl } from "@wordpress/components";

import WooCommerceRestApi from "@woocommerce/woocommerce-rest-api";

// console.log(admin_obj);
// // Authentication
const Woo = new WooCommerceRestApi({
	url: admin_obj.site_url,
	consumerKey: admin_obj.consumer_key,
	consumerSecret: admin_obj.consumer_secret,
	version: "wc/v3",
});

// List products

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const url = `products/categories/` + attributes.categoryID;
	Woo.get(url, { per_page: 100 })
		.then((response) => {
			attributes.categoryName = response.data.name;
			attributes.imageUrl = response.data.image.src;
		})
		.catch((error) => {
			console.log(error.response.data);
		});

	return (
		<>
			<div {...useBlockProps}>
				<div className="full-area-wrap">
					<div className="image-wrap">
						<img src={attributes.imageUrl} alt="" />
					</div>
					<div className="main-wrap">
						<div className="box">
							<div className="left"></div>
							<div className="main">
								<div className="name">{attributes.categoryName}</div>
								{attributes.className !== "is-style-secondary" ? (
									<div className="discover">
										<div>{attributes.promoButton}</div>
									</div>
								) : (
									""
								)}
							</div>
							<div className="right"></div>
						</div>
					</div>
				</div>
			</div>
			<InspectorControls>
				<PanelBody
					title={__("Category cover block settings", "windham-weaponry")}
					initialOpen={true}
				>
					<PanelRow>
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__("Category ID", "windham-weaponry")}
							value={attributes.categoryID}
							onChange={(value) => setAttributes({ categoryID: value })}
						/>
					</PanelRow>
					<PanelRow>
						<TextControl
							__next40pxDefaultSize
							__nextHasNoMarginBottom
							label={__("Promo Button", "windham-weaponry")}
							value={attributes.promoButton}
							onChange={(value) => setAttributes({ promoButton: value })}
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
		</>
	);
}
